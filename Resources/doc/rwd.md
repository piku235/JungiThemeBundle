Responsive Web Design (RWD)
===========================

Creating a responsive theme is an easy task. Only thing you must to do is to choose the appropriate for you theme mapping
and define a theme with two tags below:

* [**MobileDevices**](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#mobiledevices)
* [**DesktopDevices**](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#desktopdevices)

And that's all, thanks to these tags the JungiThemeBundle will notice that this theme will be used for mobile devices and
desktop devices. Additionally you can use this advantage for searching and obtaining an information about a theme.

**NOTICE**

> Even a theme with the only **DesktopDevices** tag or a theme which does not have any tags, the JungiThemeBundle will
> still use this theme for each device, but doing this sort of things is not appropriate. Is very difficult to find
> out something more about this kind of themes.

Example
-------

I know that introduction can be insufficient so I will demonstrate creating a responsive theme on the example.

### XML

```xml
<!-- FooBundle/Resources/config/theme.xml -->
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <theme name="foo" path="@JungiFooBundle/Resources/theme">
        <tags>
            <tag name="jungi.mobile_devices">
                <argument type="collection">
                    <argument>iOS</argument>
                    <argument>AndroidOS</argument>
                </argument>
                <argument type="constant">jungi.mobile_devices::MOBILE</argument>
            </tag>
            <tag name="jungi.desktop_devices" />
        </tags>
        <details>
            <detail name="author.name">piku235</detail>
            <detail name="author.email">piku235@gmail.com</detail>
            <detail name="author.www">http://test.pl</detail>
            <detail name="description"><![CDATA[<i>foo desc</i>]]></detail>
            <detail name="version">1.0.0</detail>
            <detail name="name">A fancy theme</detail>
            <detail name="license">MIT</detail>
        </details>
    </theme>
</theme-mapping>

```

### YAML

```yml
# FooBundle/Resources/config/theme.yml

parameters:
    foo.mobile.systems: [ iOS, AndroidOS ]
    foo.mobile.device: "const@jungi.mobile_devices::MOBILE"

themes:
    foo:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            - name: jungi.desktop_devices
            - name: jungi.mobile_devices
              arguments: [ "%foo.mobile.systems%", "%foo.mobile.device%" ]
        details:
            name: A fancy theme
            author:
                name: piku235
                email: piku235@gmail.com
                www: http://test.pl
            version: 1.0.0
            license: MIT
            description: <i>foo desc</i>

```

### PHP

```php
<?php
// FooBundle/Resources/config/theme.php

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Core\Details;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\Core\TagCollection;

$manager->addTheme(new Theme(
    'foo',
    $locator->locate('@JungiFooBundle/Resources/theme'),
    new Details('A fancy theme', '1.0.0', '<i>foo desc</i>', 'MIT', 'piku235', 'piku235@gmail.com', 'http://test.pl'),
    new TagCollection(array(
        new Tag\DesktopDevices(),
        new Tag\MobileDevices(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE)
    ))
));

```

Summary
-------

To see how your responsive theme works, load the theme mapping file and set the theme name to a theme resolver.