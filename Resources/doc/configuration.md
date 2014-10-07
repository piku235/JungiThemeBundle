Configuration
=============

The complete configuration:

```yaml
# app/config/config.yml
jungi_theme:

    # theme holder configuration
    holder:
        id: jungi_theme.holder.default

        # whether to ignore the situation when the theme selector will don't match any theme for the request.
        ignore_null_theme: true

    # theme selector configuration
    selector:

        # theme validation listener configuration
        validation_listener:
            enabled: true
            use_investigator: true

        # device theme switch configuration
        device_switch:
            enabled: true

    # general theme resolver configuration
    # required
    resolver:

        # fallback theme resolver configuration
        fallback:
            enabled: false
            id: ~

            # a type of theme resolver
            # one of "in_memory", "cookie", "service", "session"
            type: ~

            # arguments to be passed to the theme resolver
            arguments: []

        # theme resolver configuration
        # required
        primary:
            id: ~

            # a type of theme resolver
            # one of "in_memory", "cookie", "service", "session"
            type: ~

            # arguments to be passed to the theme resolver
            arguments: []

        # theme resolver investigator configuration
        investigator:
            enabled: true
            suspects: [ 'CookieThemeResolver' ]
```

Overview
--------

### Theme holder

A theme holder is responsible for holding the current theme instance obtained from a theme selector. You can set
your own theme holder service or leave it empty which by default points to the `jungi_theme.holder.default` service.

If a theme selector will don't match any theme for the request then it will be generated the exception `Jungi\Bundle\ThemeBundle\Exception\NullThemeException`.
If you wanna ignore this kind of situation you must set the option `ignore_null_theme` to true.

```yaml
# app/config/config.yml
jungi_theme:
    holder:
        id: # your service id
        ignore_null_theme: # true or false
```

### Theme selector

A theme selector takes the main role of resolving a theme for the request. It has a support of primary and a fallback theme
resolver where the fallback theme resolver can be unset. A fallback theme resolver will be only used when a primary theme
resolver will don't match any theme for the request. It should always return a theme name.

#### Configuration options

* the `validation_listener` option says whether a theme selector should validate themes resolved from theme resolvers.
The `use_investigator` option only says whether to use a theme resolver investigator. Thanks to this investigator we can
choose for which theme resolvers the validation should be executed. About a theme resolver investigator you can read [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/configuration.md#investigator).
* the `device_switch` option is responsible for use of the DeviceThemeSwitch listener which its job is detecting the device
which has sent the request. Thanks to this listener adaptive themes can work.

```yaml
# app/config/config.yml
jungi_theme:
    selector:
        validation_listener:
            enabled: # true or false
            use_investigator: # true or false
        device_switch: # true or false
```

### Theme resolver

We can say that a theme resolver is the heart and a theme selector is the brain of resolving theme for the request. Basically
a theme resolver allows for resolving and setting the theme name for the request.

#### Primary and fallback

We distinguish two kinds of theme resolvers: a primary and a fallback where they are almost the same. Only a fallback theme
resolver provides the `enabled` option which says whether the fallback theme resolver should be used. You can use one
of three built-in theme resolvers or use your own theme resolver by defining the `id` option which refers to a symfony
service.

The kinds of theme resolvers and how to configure them were mentioned in the [Installation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/installation.md#setup-a-built-in-theme-resolver)
chapter.

#### Investigator

A theme resolver investigator is simply used by the `Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener`
and its job is mainly identify dangerous theme resolvers like e.g. **CookieThemeResolver** where a cookie value can be easily
changed and we don't know if the theme name located in this cookie is correct or not. By default a theme investigator
is enabled and has the **CookieThemeResolver** as the default suspect. Of course you can define own suspects by changing
the `suspects` option.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        investigator:
            enabled: true
            suspects: # list of theme resolver classes
```
