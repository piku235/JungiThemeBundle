Theme resolver
==============

After you create a theme, a normal thing is that you wanna use it. To achieve that goal you will need the help of theme 
resolver. It decides about the theme that should be used for a particular request and also allows for altering the theme. 
A theme resolver is only a start point of resolving the theme, because a last word to say has a theme selector. We can 
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

Built-in theme resolvers
------------------------

The bundle comes with the following theme resolvers:

| Class |
| ----- |
| Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver |
| Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver |
| Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver |

They're mentioned in the installation steps, so if you still don't know what every of them does [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/installation.md#step-3-configuration).

Creating theme resolver
-----------------------

I will show you how to create a theme resolver on the example. Let's say that we're creating a theme resolver whose task
will be to return a theme chose by user. If the user hasn't chosen any theme then a default theme will be returned. 
However the user can be not authenticated, so lets say that the theme resolver will return null in this case.

The theme resolver could look like below:

```php
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserThemeResolver implements ThemeResolverInterface
{
    private $tokenStorage;
    private $authChecker;
    private $defaultTheme;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, $defaultTheme = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
        $this->defaultTheme = $defaultTheme;
    }

    public function resolveThemeName(Request $request)
    {
        $token = $this->tokenStorage->getToken();
        if (!$this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return;
        } 
        
        /** @var UserWithTheme $user */
        $user = $token->getUser();
        if ($themeName = $user->getThemeName()) {
            return $themeName;
        }
        
        return $this->defaultTheme;
    }
    
    public function setThemeName($themeName, Request $request)
    {
        $token = $this->tokenStorage->getToken();
        if (!$this->authChecker->isGranted('IS_AUTHENTICATED_FULLY'))) {
            throw new LogicException('You cannot change the theme when the user is not authenticated.');
        }
        
        /* @var UserWithTheme $user */
        $user = $token->getUser();
        $user->setThemeName($themeName);
    }
}
```

Now when we have created the theme resolver a normal thing is we want to use it in our project. We must create a service
for this theme resolver and activate it in the configuration. Assume that the service is called `jungi_theme.resolver.user`.
Like mentioned in the installation steps to setup a theme resolver service we must to define the configuration like below:

```yml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            id: jungi_theme.resolver.user
```

Or the shorthand version:

```yml
# app/config/config.yml
jungi_theme:
    resolver:
        primary: jungi_theme.resolver.user
```

And that's all, after this step our theme resolver should be working as expected.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)