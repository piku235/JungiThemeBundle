Theme resolver
==============

After you create a theme, a normal thing is that you wanna use it. And with the solution arrives a theme resolver. It decides 
about the theme that should be used for a particular request and also allows for altering the theme. A theme resolver is 
only the start point of resolving the theme, because the last word to say has a theme selector. We can say that a theme 
resolver is the heart and a theme selector is the brain of resolving the theme.

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