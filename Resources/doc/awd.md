Adaptive Web Design (AWD)
=========================

The AWD is right for you when you need a separate themes for different devices as e.g. the first theme for desktop devices
and the second theme for mobile devices. Thanks to the JungiThemeBundle instead of doing many things to get this properly
working you just need a few simple steps. You can have as much separate themes as you wish e.g. the first theme designed
for desktop devices, the second theme designed for mobile devices (excl. tablet devices) and the third theme designed for
tablet devices.

Explanation
-----------

The AWD in the bundle is based on following tags: **MobileDevices**, **DesktopDevices** and **VirtualTheme**. Each of 
separate themes should has a tag which describes this theme e.g. the first theme with the **DesktopDevices** tag and the 
second theme with the **MobileDevices** tag. All of these themes must also has the **VirtualTheme** tag, which basically
merges these themes into one (virtual theme). Thus, we can use such a virtual theme by setting it to a theme resolver.

Example
-------

In this example I will focus on the case when the first theme is designed for desktop devices and the second theme is
designed for mobile devices (incl. tablet devices).

### XML

```xml
<!-- FooBundle/Resources/config/theme.xml -->
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <parameters>
        <parameter key="authors" type="collection">
            <parameter type="collection">
                <parameter key="name">piku235</parameter>
                <parameter key="email">piku235@gmail.com</parameter>
                <parameter key="homepage">www.foo.com</parameter>
            </parameter>
        </parameter>
        <parameter key="license">MIT</parameter>
    </parameters>

    <themes>
        <theme name="foo_desktop" path="@JungiFooBundle/Resources/theme/desktop">
            <tags>
                <tag name="jungi.desktop_devices" />
                <tag name="jungi.virtual_theme">foo</tag>
            </tags>
            <info>
                <property key="authors">%authors%</property>
                <property key="description"><![CDATA[<i>foo desc</i>]]></property>
                <property key="version">1.0.0</property>
                <property key="name">Super theme</property>
                <property key="license">%license%</property>
            </info>
        </theme>
        <theme name="foo_mobile" path="@JungiFooBundle/Resources/theme/mobile">
            <tags>
                <tag name="jungi.mobile_devices" />
                <tag name="jungi.virtual_theme">foo</tag>
            </tags>
            <info>
                <property key="authors">%authors%</property>
                <property key="description"><![CDATA[<i>foo desc</i>]]></property>
                <property key="version">1.0.0</property>
                <property key="name">Super theme (ver. mobile)</property>
                <property key="license">%license%</property>
            </info>
        </theme>
    </themes>
    
</theme-mapping>
```

### YAML

```yml
# FooBundle/Resources/config/theme.yml
parameters:
    license: MIT
    authors:
        - { name: piku235, email: piku235@gmail.com, homepage: www.foo.com }

themes:
    foo_desktop:
        path: "@JungiFooBundle/Resources/theme/desktop"
        tags:
            jungi.desktop_devices: ~
            jungi.virtual_theme: foo
        info:
            authors: "%authors%"
            name: Super theme
            version: 1.0.0
            license: "%license%"
            description: <i>foo desc</i>
    foo_mobile:
        path: "@JungiFooBundle/Resources/theme/mobile"
        tags:
            jungi.mobile_devices: ~
            jungi.virtual_theme: foo
        info:
            authors: "%authors%"
            name: Super theme (ver. mobile)
            version: 1.0.0
            license: "%license%"
            description: <i>foo desc</i>

```

### PHP

```php
<?php
// FooBundle/Resources/config/theme.php

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

$ib = ThemeInfoEssence::createBuilder();
$ib
    ->setName('Super theme')
    ->setDescription('<i>foo desc</i>')
    ->setVersion('1.0.0')
    ->setLicense('MIT')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'));

$manager->registerTheme(new Theme(
    'foo',
    $locator->locate('@JungiFooBundle/Resources/theme/desktop'),
    $ib->getThemeInfo(),
    new TagCollection(array(
        new Tag\VirtualTheme('foo'),
        new Tag\DesktopDevices(),
    ))
));

$ib->setName('Super theme (ver. mobile)');
$manager->registerTheme(new Theme(
    'foo_mobile',
    $locator->locate('@JungiFooBundle/Resources/theme/mobile'),
    $ib->getThemeInfo(),
    new TagCollection(array(
        new Tag\VirtualTheme('foo'),
        new Tag\MobileDevices()
    ))
));
```

Summary
-------

Finally to get it working you must set up the virtual theme name (in the example it's the "@foo") to a theme resolver and 
of course load the theme mapping file.

**NOTE**

> Remember that to use a virtual theme, you have to precede its name by the character "@".

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)