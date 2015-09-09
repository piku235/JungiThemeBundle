Configuration
=============

```yaml
# app/config/config.yml
jungi_theme:

    # theme source configuration
    source:

        # symfony service id
        id:                   ~

    # theme holder configuration
    holder:

        # symfony service id
        id:                   ~

        # whether to ignore a situation when the theme selector will do not match any theme for the request.
        ignore_null_theme:    true

    # theme selector configuration
    selector:

        # symfony service id
        id:                   ~

        # theme validation listener configuration
        validation_listener:
            enabled:              false

            # a list of theme resolvers which should be validated
            suspects:             []

    # general theme resolver configuration
    resolver:             # Required

        # virtual theme resolver configuration
        virtual:

            # symfony service id
            id:                   ~

            # use the device theme filter
            device_filter:        true

        # theme resolver configuration
        primary:              # Required

            # symfony service id
            id:                   ~

            # cookie theme resolver
            cookie:
                lifetime:             2592000
                path:                 /
                domain:               ~
                secure:               false
                httpOnly:             true

            # in memory theme resolver
            in_memory:            ~

            # session theme resolver
            session:              ~

        # fallback theme resolver configuration
        fallback:

            # symfony service id
            id:                   ~

            # cookie theme resolver
            cookie:
                lifetime:             2592000
                path:                 /
                domain:               ~
                secure:               false
                httpOnly:             true

            # in memory theme resolver
            in_memory:            ~

            # session theme resolver
            session:              ~

    # list of theme mapping files
    mappings:
        type:                 null
        resource:             ~ # Required

    # list of tag classes that will be registered
    tags:                 []
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)