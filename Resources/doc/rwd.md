Responsive Web Design (RWD)
===========================

To create a responsive theme you have only to choose the appropriate theme mapping and define inside this theme mapping
document a theme with two tags below:

* [MobileDevices](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#mobiledevices)
* [DesktopDevices](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#desktopdevices)

And that's all, thanks to these tags the JungiThemeBundle will notice that the theme will be used for mobile devices and
desktop devices. Additionally you can use this advantage for searching and obtaining an information about a theme.

**INFO**

> Even a theme without any tags can be selected for a dispatched request if there wasn't any previous theme match

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

    <parameters>
        <parameter key="authors" type="collection">
            <parameter>
                <parameter key="name">piku235</parameter>
                <parameter key="email">piku235@gmail.com</parameter>
                <parameter key="homepage">www.foo.com</parameter>
            </parameter>
        </parameter>
    </parameters>

    <themes>
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
                <property key="authors">%authors%</property>
                <property key="description"><![CDATA[<i>foo desc</i>]]></property>
                <property key="version">1.0.0</property>
                <property key="name">A fancy theme</property>
                <property key="license">MIT</property>
            </details>
        </theme>
    </themes>
    
</theme-mapping>

```

### YAML

```yml
# FooBundle/Resources/config/theme.yml
parameters:
    authors:
        - { name: piku235, email: piku235@gmail.com, homepage: www.foo.com }
    foo.mobile_systems: [ iOS, AndroidOS ]
    foo.mobile_device: "const@jungi.mobile_devices::MOBILE"

themes:
    foo:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            jungi.desktop_devices: ~
            jungi.mobile_devices: [ "%foo.mobile_systems%", "%foo.mobile_device%" ]
        details:
            authors: "%authors%"
            name: A fancy theme
            version: 1.0.0
            license: MIT
            description: <i>foo desc</i>

```

### PHP

```php
<?php
// FooBundle/Resources/config/theme.php

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Details\Details;
use Jungi\Bundle\ThemeBundle\Details\Author;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

$dsb = Details::createBuilder();
$dsb
    ->setName('A fancy theme')
    ->setDescription('<i>foo desc</i>')
    ->setVersion('1.0.0')
    ->setLicense('MIT')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'));

$manager->addTheme(new Theme(
    'foo',
    $locator->locate('@JungiFooBundle/Resources/theme'),
    $dsb->getDetails(),
    new TagCollection(array(
        new Tag\DesktopDevices(),
        new Tag\MobileDevices(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE)
    ))
));

```

Summary
-------

To see how your responsive theme works, load the created theme mapping file and set the theme name to a theme resolver.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)