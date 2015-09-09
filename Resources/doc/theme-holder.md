Theme holder
============

A theme holder is a place where the current theme instance is kept. By default the current theme is resolved by theme 
selector. You can access a theme holder via the `jungi_theme.holder` service. 

A theme holder implements the `Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface`. 

```php
interface ThemeHolderInterface
{
    /**
     * Returns the current theme
     *
     * @return ThemeInterface|null Null if the theme was not set
     */
    public function getTheme();

    /**
     * Sets the current theme
     *
     * @param ThemeInterface $theme A theme
     *
     * @return void
     */
    public function setTheme(ThemeInterface $theme);
}
```

### Default implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/ThemeHolder.php)

The `Jungi\Bundle\ThemeBundle\Core\ThemeHolder` is the default theme holder which has only basic methods contained
in the interface. Of course you can change it by setting your own theme holder service in the configuration.

### Configuration

To set your own theme holder you must only set the `id` to an appropriate symfony service.

The `ignore_null_theme` option has only meaning when a theme selector will not match any theme for the request. By default
a theme selector will throw the exception `Jungi\Bundle\ThemeBundle\Exception\NullThemeException` in this situation. If 
you wanna ignore that behaviour you must set the `ignore_null_theme` to true.

```yaml
# app/config/config.yml
jungi_theme:
    holder:
        id: # your service id
        ignore_null_theme: # true or false
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)