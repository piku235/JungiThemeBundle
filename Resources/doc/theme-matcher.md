Theme matcher
=============

As we know a theme resolver returns the theme name for a particular request. The most common case is that a theme name 
points to an existing theme, but how you'll see it's not sufficient though. When we've adaptive themes (AWD) there is not 
know which of them will be matched, because it depends on a device which has sent the request. With the solution comes 
a theme matcher which is responsible for matching a theme instance based on a theme name and the request.

A theme matcher implements the `Jungi\Bundle\ThemeBundle\Matcher\ThemeMatcherInterface`.

```php
interface ThemeMatcherInterface
{
    /**
     * Checks whether a given theme name is supported by the matcher
     *
     * @param string $themeName A theme name
     *
     * @return bool
     */
    public function supports($themeName);

    /**
     * Matches an appropriate theme based on a given theme name for a given Request
     *
     * @param string|ThemeNameReferenceInterface $themeName A theme name
     * @param Request                            $request   A Request instance
     *
     * @return ThemeInterface
     *
     * @throws ThemeNotFoundException If none matched
     */
    public function match($themeName, Request $request);
}
```

StandardThemeMatcher
--------------------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Matcher/StandardThemeMatcher.php)

This matcher is very simple and it's used to match an appropriate theme instance based only on a given theme name, so for 
the theme name "footheme" the theme matcher will match a theme instance with the "footheme" name.

VirtualThemeMatcher
-------------------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Matcher/VirtualThemeMatcher.php)

In comparison with the previous one this theme matcher is more complex. As the name says it handles virtual theme
names. A virtual theme name is preceded by the character "@" and it refers to a theme that doesn't really exist. To say 
more precisely a virtual theme name refers to a collection of themes where only one of this collection will be matched 
at the final stage. All of this is possible thanks to the tag `Jungi\Bundle\ThemeBundle\Tag\VirtualTheme`. To reduce such 
a collection of themes, the theme matcher uses theme filters like DeviceThemeFilter. 

### DeviceThemeFilter

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Matcher/Filter/DeviceThemeFilter.php)

This listener is mandatory for properly work adaptive themes (AWD). When the theme has been resolved then the listener
will try to find a better matching theme. The search will be only executed when the resolved theme doesn't support a device
which has sent the request.

Configuration
-------------

```yaml
# app/config/config.yml
jungi_theme:
    matcher:
        id: # theme matcher service id
        device_filter: # use the device theme filter, true or false
```
