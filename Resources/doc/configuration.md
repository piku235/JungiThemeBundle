Configuration
=============

```yaml
# app/config/config.yml
jungi_theme:

    # theme holder configuration
    holder:

        # theme holder service id
        id: jungi_theme.holder.default

        # whether to ignore the situation when the theme selector will not match any theme for the request.
        ignore_null_theme: true

    # theme matcher configuration
    matcher:

        # theme matcher service id
        id: ~

        # device theme filter configuration
        device_filter:
            enabled: true

    # theme selector configuration
    selector:

        # theme selector service id
        id: ~

        # theme validation listener configuration
        validation_listener:
            enabled: false

            # a list of theme resolvers which should be validated
            suspects: []

    # general theme resolver configuration
    resolver: # Required

        # fallback theme resolver configuration
        fallback:
            enabled: false

            # theme resolver service id
            id: ~

            # a type of theme resolver
            type: ~ # One of "in_memory"; "cookie"; "service"; "session"

            # arguments to be passed to the theme resolver
            arguments: []

        # theme resolver configuration
        primary: # Required

            # theme resolver service id
            id: ~

            # a type of theme resolver
            type: ~ # One of "in_memory"; "cookie"; "service"; "session"

            # arguments to be passed to the theme resolver
            arguments: []

```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)