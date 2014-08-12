Configuration
=============

The complete configuration:

```yaml
# app/config/config.yml
jungi_theme:
    # theme holder configuration
    holder:
        id: jungi.theme.holder.default

    # theme selector configuration
    selector:

        # whether to ignore null theme names, when a theme resolver does not return any theme name.
        ignore_null_themes: true

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
your own theme holder service or leave it empty which by defaults points to the `jungi.theme.holder.default` service.

```yaml
# app/config/config.yml
jungi_theme:
    holder: # your service id
```

or

```yaml
# app/config/config.yml
jungi_theme:
    holder:
        id: # your service id
```

### Theme selector

A theme selector takes the main role of selecting theme for the request. It has the support of primary and fallback theme
resolver where the fallback theme resolver can be unset. A fallback theme resolver will be only used when a primary theme
resolver will does not match any theme for the request. A fallback theme resolver should always return some theme name.
Also a theme selector provides events to which you can attach your own event listeners.

#### Configuration options

* when the `ignore_null_themes` option is set to false a theme selector will not throw the exception with the missing
theme name. This situation may take place when a theme resolver will does not match any theme for a dispatched request.
* the `validation_listener` option says whether a theme selector should validate themes resolved from the theme resolvers.
The `use_investigator` option only says whether to use a theme resolver investigator. Thanks to this investigator we can
choose for which theme resolvers the validation should be executed. About a theme resolver investigator you can read [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/configuration.md#investigator).
* the `device_switch` is responsible for the use of the DeviceThemeSwitch listener which its job is detecting the device
which has sent the request. Thanks to this listener adaptive themes can work.

```yaml
# app/config/config.yml
jungi_theme:
    selector:
        ignore_null_themes: # true or false
        validation_listener:
            enabled: # true or false
            use_investigator: # true or false
        device_switch: # true or false
```

### Theme resolver

We can say that a theme resolver is the heart and a theme selector is the brain of resolving theme for the request. A theme
resolver determines a theme name for the request and allows for changing the theme name.

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
is enabled and has the **CookieThemeResolver** as the default suspect. Of course you can define own suspects by changing the
`suspects` option.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        investigator:
            enabled: true
            suspects: # list of theme resolver classes
```
