Theme resolver investigator
===========================

A theme resolver investigator is simply used by the `Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener`
and its job is mainly identify dangerous theme resolvers like e.g. **CookieThemeResolver** where a cookie value can be easily
changed and we don't know if the theme name located in this cookie is correct or not. By default a theme investigator
is enabled and has the **CookieThemeResolver** as the default suspect. Of course you can define own suspects by changing
the `suspects` option.

### Configuration

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        investigator:
            enabled: true
            suspects: # list of theme resolver classes
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)