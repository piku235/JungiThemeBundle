Theme selector
==============

Basically a theme selector doesn't differ much from a theme resolver. The difference between them is that a theme
selector returns the **theme instance** for a given Request. A theme selector is a class which implements the
`Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorInterface`.
 
```php
interface ThemeSelectorInterface
{
    /**
     * Selects an appropriate theme for a given Request
     *
     * @param Request $request A Request instance
     *
     * @return \Jungi\Bundle\ThemeBundle\Core\ThemeInterface
     *
     * @throws NullThemeException If there is no any matching theme for the request
     */
    public function select(Request $request);
}
```

### Default implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Selector/ThemeSelector.php)

The default theme selector is the class `Jungi\Bundle\ThemeBundle\Selector\ThemeSelector`. It has a support of primary and 
fallback theme resolver where the fallback theme resolver can be unset. If a fallback theme resolver was set then it'll 
be only used when the primary theme resolver will don't match any theme for the request. Also this theme selector supports 
events, where you can find them in the class `Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorEvents` ([click here](https://github.com/piku235/JungiThemeBundle/blob/master/Selector/ThemeSelectorEvents.php)).

#### Primary and fallback theme resolver

Generally a primary and a fallback theme resolver are the same, so you can set for them any theme resolver you want. 
By default the fallback theme resolver is disabled, so if you gonna to use it you must remember to set the `enabled` to 
true in the configuration. 

How to setup and which theme resolvers are in the bundle were mentioned in the [installation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/installation.md#step-3-configuration)
steps.

#### Configuration

```yaml
# app/config/config.yml
jungi_theme:
    selector:
        id: # theme selector service
        validation_listener:
            enabled: # true or false
            use_investigator: # true or false
        device_switch: # true or false
```

* The `id` is used to set a theme selector service by typing its service id.
* The `validation_listener` says whether a theme selector should validate themes resolved from theme resolvers. The 
`use_investigator` only says whether to use a theme resolver investigator. Thanks to this investigator we can choose for
which theme resolvers the validation should be executed. About a theme resolver investigator you can read [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-resolver-investigator.md).
* The `device_switch` is responsible for use of the `DeviceThemeSwitch` listener which its job is detecting the device
which has sent the request. Thanks to this listener adaptive themes can work.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)