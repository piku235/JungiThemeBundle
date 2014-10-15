Configuration
=============

```yaml
# app/config/config.yml
jungi_theme:

    # theme holder configuration
    holder:

        # theme holder service id
        id: jungi_theme.holder.default

        # whether to ignore the situation when the theme selector will don't match any theme for the request.
        ignore_null_theme: true

    # theme selector configuration
    selector:

        # theme selector service id
        id: ~

        # theme validation listener configuration
        validation_listener:
            enabled: false
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

            # theme resolver service id
            id: ~

            # a type of theme resolver
            # one of "in_memory", "cookie", "service", "session"
            type: ~

            # arguments to be passed to the theme resolver
            arguments: []

        # theme resolver configuration
        # required
        primary:

            # theme resolver service id
            id: ~

            # a type of theme resolver
            # one of "in_memory", "cookie", "service", "session"
            type: ~

            # arguments to be passed to the theme resolver
            arguments: []

        # theme resolver investigator configuration
        investigator:
            enabled: false
            suspects: 
            
                # Default:
                - Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver
```
