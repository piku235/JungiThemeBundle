Theme resolver investigator
===========================

A theme resolver investigator job is mainly identify dangerous theme resolvers like e.g. the **CookieThemeResolver** where 
a cookie value can be easily changed and we don't know if the theme name located in this cookie is correct or not. It's
mostly used by the `Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener`.

A theme resolver investigator implements the `Jungi\Bundle\ThemeBundle\Resolver\Investigator\ThemeResolverInvestigatorInterface` 
and it can be used via the service `jungi_theme.resolver.investigator`.

```php
interface ThemeResolverInvestigatorInterface
{
    /**
     * Checks if a given theme resolver is suspect
     *
     * @param ThemeResolverInterface $resolver A theme resolver
     *
     * @return boolean
     */
    public function isSuspect(ThemeResolverInterface $resolver);
}
```

### Configuration

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        investigator:
            suspects: # list of theme resolver classes
```

The `suspects` for the **CookieThemeResolver** can looks like the following:

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        investigator:
            suspects: [ 'CookieThemeResolver' ]
```

You can use shorthand class names just like above only for those theme resolvers which are located in the JungiThemeBundle.
Normally you must write a fully qualified class name.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)