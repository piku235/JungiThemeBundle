Adaptive Web Design (AWD)
=========================

The AWD is right for you when you need a separate themes for different devices e.g. a first one for desktop devices
and a second one for mobile devices. Thanks to the JungiThemeBundle instead of doing many things to get this properly
working you just need a few simple steps. You can have as much separate themes as you wish e.g. a first theme designed
for desktop devices, a second theme designed for mobile devices (excl. tablet devices) and a third theme designed for
tablet devices.

Explanation
-----------

Each of separate themes should have a tag which describes this theme e.g. a first theme will has the **DesktopDevices**
tag and a second theme will has the **MobileDevices** tag. Almost all of these themes must implement the **Link** tag
to create a connection between them, expect one theme which don't have to implement this tag. This theme behaves like
the representative of all connected themes, like the main theme. Which of these themes will be the representative theme
depends only on you.

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
        <theme name="foo_main" path="@JungiFooBundle/Resources/theme/desktop">
            <tags>
                <tag name="jungi.desktop_devices" />
            </tags>
            <metadata>
                <property key="authors">%authors%</property>
                <property key="description"><![CDATA[<i>foo desc</i>]]></property>
                <property key="version">1.0.0</property>
                <property key="name">Super theme</property>
                <property key="license">%license%</property>
            </metadata>
        </theme>
        <theme name="foo_mobile" path="@JungiFooBundle/Resources/theme/mobile">
            <tags>
                <tag name="jungi.mobile_devices" />
                <tag name="jungi.link">foo_main</tag>
            </tags>
            <metadata>
                <property key="authors">%authors%</property>
                <property key="description"><![CDATA[<i>foo desc</i>]]></property>
                <property key="version">1.0.0</property>
                <property key="name">Super theme (ver. mobile)</property>
                <property key="license">%license%</property>
            </metadata>
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
    foo_main:
        path: "@JungiFooBundle/Resources/theme/desktop"
        tags:
            jungi.desktop_devices: ~
        metadata:
            authors: "%authors%"
            name: Super theme
            version: 1.0.0
            license: "%license%"
            description: <i>foo desc</i>
    foo_mobile:
        path: "@JungiFooBundle/Resources/theme/mobile"
        tags:
            jungi.mobile_devices: ~
            jungi.link: foo_main
        metadata:
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
use Jungi\Bundle\ThemeBundle\Metadata\Metadata;
use Jungi\Bundle\ThemeBundle\Metadata\Author;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

$mb = Metadata::createBuilder();
$mb
    ->setName('Super theme')
    ->setDescription('<i>foo desc</i>')
    ->setVersion('1.0.0')
    ->setLicense('MIT')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'));

$manager->addTheme(new Theme(
    'foo_main',
    $locator->locate('@JungiFooBundle/Resources/theme/desktop'),
    $mb->getMetadata(),
    new TagCollection(array(
        new Tag\DesktopDevices(),
    ))
));

$mb->setName('Super theme (ver. mobile)');
$manager->addTheme(new Theme(
    'foo_mobile',
    $locator->locate('@JungiFooBundle/Resources/theme/mobile'),
    $mb->getMetadata(),
    new TagCollection(array(
        new Tag\Link('foo_main'),
        new Tag\MobileDevices()
    ))
));
```

Summary
-------

Thanks to the built-in utilities creating adaptive themes is very convenient and fast. Finally you must set the theme
name (the representative theme) to a theme resolver and load the theme mapping file to see how it works.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)