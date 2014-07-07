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

A theme holder is responsible for holding a current theme instance obtained from a dispatched request. You can set
your own theme holder service or leave it empty which by defaults points to the `jungi.theme.holder.default` service.

```yaml
jungi_theme:
   holder: # your service id
```

or

```yaml
jungi_theme:
    holder:
        id: # your service id
```

### Theme selector

A theme selector takes the main role of selecting a theme for a request. It has support of a primary and a fallback theme
resolver where the fallback theme resolver can be unset. The fallback theme resolver will be only used when the primary
theme resolver will don't match any theme for a dispatched request. The fallback theme resolver should always return some
theme name. Also the theme selector provides events to which you can attach your own event listeners.

#### Configuration options

* when the `ignore_null_themes` option is set to false then a theme selector will not throw an exception with missing
theme name. This situation may take place when a theme resolver don't match any theme for a request.
* the `validation_listener` option says whether a theme selector should validate themes resolved from theme resolvers.
Thanks to the `use_investigator` option we can choose for which theme resolvers the validation should be executed. About
a theme resolver investigator you can read [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/configuration.md#investigator).

### Theme resolver

We can say that a theme resolver is a heart and a theme selector is a brain of selecting a theme for a request. The theme
resolver returns only a theme name for a dispatched request or nothing (null) if there wasn't any match for the request.

#### Primary and fallback

We distinguish two kinds of theme resolvers: primary and fallback where they are almost the same. Only the fallback theme
resolver provides the `enabled` option which says whether the fallback theme resolver should be used. You can use one
of three built-in theme resolvers or use your own theme resolver by defining the `id` option which refers to a symfony
service.

##### Service

```yaml
jungi_theme:
    resolver:
        primary:
            id: # your theme resolver service
```

##### Cookie

Cookie theme resolver accepts only one argument which is of an array type and it's responsible for cookie options.

```yaml
jungi_theme:
    resolver:
        primary:
            type: cookie
            arguments:
                - lifetime: 3600 # time in sec
                  path: /
                  domain: ~
                  secure: false
                  httpOnly: true
```

##### InMemory

InMemory theme resolver accepts two arguments where the first one takes a theme name and a second one decides whether
the theme name can be changed or not. The second argument was only introduced for tests benefits.

```yaml
jungi_theme:
    resolver:
        primary:
            type: in_memory
            arguments: foo_theme # a theme name
```

##### Session

Session theme resolver doesn't has any arguments, so the `arguments` option don't must be provided.

```yaml
jungi_theme:
    resolver:
        primary:
            type: session
```

#### Investigator

A theme resolver investigator is simply used by the `Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener`
and its job is mainly identify the dangerous theme resolvers like for eg **CookieThemeResolver** where cookie value
can be easily changed and we don't know if the theme name located in this cookie is correct or not. By default the theme
investigator is enabled and has the **CookieThemeResolver** as the suspect. Of course you can define own suspects by
changing the `suspects` option.

```yaml
jungi_theme:
    resolver:
        investigator:
            enabled: true
            suspects: # list of theme resolver classes
```
