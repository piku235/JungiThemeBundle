Virtual theme resolver
======================

A virtual theme resolver is used to determine the pointed theme of a virtual theme. 

```php
interface VirtualThemeResolverInterface
{
    /**
     * Resolves a suitable theme for the given virtual theme.
     *
     * @param VirtualThemeInterface $theme   A virtual theme
     * @param Request               $request A Request instance
     *
     * @return ThemeInterface
     */
    public function resolveTheme(VirtualThemeInterface $theme, Request $request);
}
```

A default implementation is the `Jungi\Bundle\ThemeBundle\Resolver\VirtualThemeResolver` which uses filters to resolve
the best matching theme for a virtual theme.

Filters
-------

A job of filters is to reduce a given theme collection as much as possible. Filters will be used as long as in a theme 
collection will stay only one theme.

All theme filters implements the `Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeFilterInterface`.

```php
interface ThemeFilterInterface
{
    /**
     * Filters the given theme collection by removing these themes that are not suitable
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

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Resolver/Filter/DeviceThemeFilter.php)

This filter is mandatory for working adaptive themes (AWD) properly. The filter will only work for themes that has at 
least one of these tags: **MobileDevices**, **TabletDevices** or **DesktopDevices**.

Configuration
-------------

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        # virtual theme resolver
        virtual:
            id: # symfony service id
            device_filter: # use the device theme filter? True or false
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)