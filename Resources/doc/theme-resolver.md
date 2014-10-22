Theme resolver
==============

After you create a theme, a normal thing is that you wanna use it. To achieve that goal you will need the help of theme 
resolver. It decides about the theme that should be used for a particular request and also allows for altering the theme. 
A theme resolver is only the start point of resolving the theme, because the last word to say has a theme selector. We can 
say that a theme resolver is the heart and a theme selector is the brain of resolving the theme.

All theme resolvers must implement the `Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface`. 

```php
interface ThemeResolverInterface
{
    /**
     * Returns the appropriate theme name for a given request
     *
     * @param Request $request A request instance
     *
     * @return string|null Returns null if a theme name is not set
     */
    public function resolveThemeName(Request $request);

    /**
     * Sets the theme for a given request
     *
     * @param string  $themeName The theme name
     * @param Request $request   A request instance
     *
     * @return void
     */
    public function setThemeName($themeName, Request $request);
}
```

### Built-in theme resolvers

The bundle comes with the following theme resolvers:

| Class |
| ----- |
| Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver |
| Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver |
| Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver |

They're mentioned in the installation steps, so if you still don't know what every of them does [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/installation.md#step-3-configuration).

### Create theme resolver

I will show you how to create a theme resolver on the example. Let's say that we're creating a theme resolver whose task
will be to return a theme chosen by user. If the user hasn't chosen any theme then a default theme for users will be 
returned. However a user can be not authenticated, then let us assume that the theme resolver will return null.

The theme resolver could look like below:

```php
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;

class UserThemeResolver implements ThemeResolverInterface
{
    private $securityContext;
    private $defaultTheme;

    public function __construct(SecurityContextInterface $securityContext, $defaultTheme = null)
    {
        $this->securityContext = $securityContext;
        $this->defaultTheme = $defaultTheme;
    }

    public function resolveThemeName(Request $request)
    {
        $token = $this->securityContext->getToken();
        if (!$token->isGranted(new Expression('is_authenticated()'))) {
            return null;
        } 
        
        /* @var UserWithTheme $user */
        $user = $token->getUser();
        if ($themeName = $user->getThemeName()) {
            return $themeName;
        }
        
        return $this->defaultTheme;
    }
}
```

Now when we have our theme resolver created a normal thing is we want use it in a project. We must create a service
for this theme resolver and activate it in the configuration. Assume that the service is called `foo_vendor.resolver.user`.
Like mentioned in the installation steps to setup a theme resolver service we must to define the theme resolver configuration
like below:

```yml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            id: foo_vendor.resolver.user
```

Or the shorthand version:

```yml
# app/config/config.yml
jungi_theme:
    resolver:
        primary: foo_vendor.resolver.user
```

And that's all, after this step our theme resolver should be normally working.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)