Theme Holder
============

As you should know a theme holder is used to hold the current theme instance. By default the current theme
is resolved by theme selector. To access a theme holder you have to use the service `jungi_theme.holder`.

Basically a theme holder is a class which implements the `Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface`. With this
interface you can easily create your own theme holder.

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

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/SimpleThemeHolder.php)

The `Jungi\Bundle\ThemeBundle\Core\SimpleThemeHolder` is the default theme holder which has only basic methods contained
in the interface. Of course you can change it by setting your own theme holder service in the configuration.

### Configuration

To set your own theme holder you must only set the `id` to the appropriate symfony service.

The `ignore_null_theme` has only meaning when a theme selector will don't match any theme for the request. By default a
theme selector will throw the exception `Jungi\Bundle\ThemeBundle\Exception\NullThemeException` in this kind of situation.
If you wanna ignore that you must set the `ignore_null_theme` to true.

```yaml
# app/config/config.yml
jungi_theme:
    holder:
        id: # your service id
        ignore_null_theme: # true or false
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)