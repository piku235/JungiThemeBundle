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

A theme selector behaves like a wrapper for a theme resolver. It takes the main role of theme selecting for a request.
* when the **ignore_null_themes** option is set to false then a theme selector will not throw an exception with missing
theme name. This situation may take place when a theme resolver will return null.