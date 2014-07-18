Adaptive Web Design (AWD)
=========================

The AWD is right for you when you need a separate themes for different devices e.g. the first one for desktop devices
and the second one for mobile devices. Thanks to the JungiThemeBundle instead of doing many things to get this properly
working you just need a few simple steps. You can have as much separate themes as you wish e.g. a first theme designed
for desktop devices, a second theme designed for mobile devices (excl. tablet devices) and a third theme designed for
tablet devices.

Explanation
-----------

Each of separate themes should have a tag which describes this theme e.g. a first theme will has the **DesktopDevices**
tag and a second theme will has the **MobileDevices** tag. Almost all of these themes must implement the **Link** tag
to create a connection between them, expect one theme which don't have to implement the **Link** tag. This theme behaves
like a representative of all connected themes, like the main theme. Which of these themes will be the representative theme
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

    <theme name="foo_main" path="@JungiFooBundle/Resources/theme/desktop">
        <tags>
            <tag name="jungi.desktop_devices" />
        </tags>
        <details>
            <detail name="author.name">piku235</detail>
            <detail name="author.email">piku235@gmail.com</detail>
            <detail name="author.site">http://test.pl</detail>
            <detail name="description"><![CDATA[<i>foo desc</i>]]></detail>
            <detail name="version">1.0.0</detail>
            <detail name="name">A fancy theme</detail>
            <detail name="license">MIT</detail>
        </details>
    </theme>
    <theme name="foo_mobile" path="@JungiFooBundle/Resources/theme/mobile">
        <tags>
            <tag name="jungi.mobile_devices" />
            <tag name="jungi.link">foo_main</tag>
        </tags>
        <details>
            <detail name="author.name">piku235</detail>
            <detail name="author.email">piku235@gmail.com</detail>
            <detail name="author.site">http://test.pl</detail>
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

themes:
    foo_main:
        path: "@JungiFooBundle/Resources/theme/desktop"
        tags:
            - name: jungi.desktop_devices
        details:
            name: A fancy theme
            author:
                name: piku235
                email: piku235@gmail.com
                www: http://test.pl
            version: 1.0.0
            license: MIT
            description: <i>foo desc</i>
    foo_mobile:
        path: "@JungiFooBundle/Resources/theme/mobile"
        tags:
            - name: jungi.mobile_devices
            - name: jungi.link
              arguments: foo_main
        details:
            name: A fancy theme
            author:
                name: piku235
                email: piku235@gmail.com
                site: http://test.pl
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
    'foo_main',
    $locator->locate('@JungiFooBundle/Resources/theme/desktop'),
    new Details(array(
        'name' => 'A fancy desktop theme',
        'version' => '1.0.0',
        'description' => '<i>foo desc</i>',
        'license' => 'MIT',
        'author.name' => 'piku235',
        'author.email' => 'piku235@gmail.com',
        'author.site' => 'http://test.pl'
    )),
    new TagCollection(array(
        new Tag\DesktopDevices(),
    ))
));
$manager->addTheme(new Theme(
    'foo_mobile',
    $locator->locate('@JungiFooBundle/Resources/theme/mobile'),
    new Details(array(
        'name' => 'A fancy mobile theme',
        'version' => '1.0.0',
        'description' => '<i>foo desc</i>',
        'license' => 'MIT',
        'author.name' => 'piku235',
        'author.email' => 'piku235@gmail.com',
        'author.site' => 'http://test.pl'
    )),
    new TagCollection(array(
        new Tag\Link('foo_main'),
        new Tag\MobileDevices()
    ))
));
```

Summary
-------

Thanks to the built-in utilities creating an adaptive themes is very convenient and fast. Finally you must set a theme
name (the representative theme) to a theme resolver and load the theme mapping file to see how it works.