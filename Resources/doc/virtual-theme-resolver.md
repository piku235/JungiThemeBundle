Virtual theme resolver
======================

A theme matcher implements the `Jungi\Bundle\ThemeBundle\Resolver\VirtualThemeResolverInterface`.

```php
interface VirtualThemeResolverInterface
{
    /**
     * Resolved an appropriate theme for a given virtual theme
     *
     * @param VirtualThemeInterface $theme   A virtual theme
     * @param Request               $request A Request instance
     *
     * @return ThemeInterface
     */
    public function resolveTheme(VirtualThemeInterface $theme, Request $request);
}
```

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

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Resolver/Filter/DeviceThemeFilter.php)

The filter is mandatory for working adaptive themes (AWD) properly. The filter will only work for themes which has the 
**MobileDevices** tag or the **DesktopDevices** tag. If such a theme will not meet the requirements of the filter, 
the theme will be removed from the collection and thereby, the collection of themes will be reduced.

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