Theme matcher
=============

As we know a theme resolver returns the theme name for a particular request. The most common case is that a theme name 
refers to an existing theme, but how you'll see it's not sufficient though. When we have adaptive themes (AWD) there is 
not know which of them will be matched, because it depends on a device which has sent the request. With the solution comes 
a theme matcher which is responsible for matching a theme instance based on a theme name and the request.

A theme matcher implements the `Jungi\Bundle\ThemeBundle\Matcher\ThemeSetMatcherInterface`.

```php
interface ThemeSetMatcherInterface
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

ThemeSetMatcher
-------------------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Matcher/ThemeSetMatcher.php)

In comparison with the previous one this theme matcher is more complex. As the name says it handles virtual theme
names. A virtual theme name is preceded by the character "@" and it refers to a theme that doesn't really exist. To say 
more precisely a virtual theme name refers to a collection of themes where only one of this collection will be matched 
at the final stage. All of this is possible thanks to the tag `Jungi\Bundle\ThemeBundle\Tag\VirtualTheme`. To reduce such 
a collection of themes, the theme matcher uses theme filters like **DeviceThemeFilter**. 

Filters
-------

Each theme filter must implement the `Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeFilterInterface`.

```php
interface ThemeFilterInterface
{
    /**
     * Filters a given theme collection by removing these themes that are not suitable'
     *
     * @param ThemeCollection $themes  A theme collection
     * @param Request         $request A Request instance
     *
     * @return void
     */
    public function filter(ThemeCollection $themes, Request $request);
}
```

### DeviceThemeFilter

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Matcher/Filter/DeviceThemeFilter.php)

The filter is mandatory for working adaptive themes (AWD) properly. The filter will only work for themes which has the 
**MobileDevices** tag or the **DesktopDevices** tag. If such a theme will not meet the requirements of the filter, 
the theme will be removed from the collection and thereby, the collection of themes will be reduced.

Configuration
-------------

```yaml
# app/config/config.yml
jungi_theme:
    matcher:
        id: # theme matcher service id
        # virtual theme matcher
        virtual:
            # use the device theme filter
            device_filter: # true or false
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)