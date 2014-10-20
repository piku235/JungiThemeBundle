Theme selector
==============

A theme selector takes the main role of resolving a theme for the request. 

### Default implementation

The default theme selector is the class `Jungi\Bundle\ThemeBundle\Selector\ThemeSelector`. 
It has a support of primary and a fallback theme
resolver where the fallback theme resolver can be unset. A fallback theme resolver will be only used when a primary theme
resolver will don't match any theme for the request.

#### Primary and fallback theme resolver

We distinguish two kinds of theme resolvers: a primary and a fallback where they are almost the same. Only a fallback theme
resolver provides the `enabled` option which says whether the fallback theme resolver should be used. You can use one
of three built-in theme resolvers or use your own theme resolver by defining the `id` which refers to a symfony service.

The kinds of theme resolvers and how to configure them were mentioned in the [Installation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/installation.md#setup-a-built-in-theme-resolver)
chapter.

#### Configuration

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

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)