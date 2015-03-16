Adaptive Web Design (AWD)
=========================

The AWD is right for you when you need separate themes for different devices as e.g. the first theme for desktop devices
and the second theme for mobile devices. Thanks to the JungiThemeBundle instead of doing many things to get this properly
working you just need a few simple steps. You can have as much separate themes as you wish e.g. the first theme designed
for desktop devices, the second theme designed for mobile devices (excl. tablet devices) and the third theme designed for
tablet devices.

Explanation
-----------

The AWD in the bundle is based on the following tags: **MobileDevices**, **TabletDevices** and **DesktopDevices**. Each of 
these separate themes should has a tag which describes this theme e.g. the first theme with the **DesktopDevices** tag 
and the second theme with the **MobileDevices** tag. These separate themes will be not working yet, because at this shape
they are still not visible for the bundle. To make this working we need a virtual theme, which binds these themes
together, thereby they behaves as a single theme. We can use such a virtual theme in a normal way as we have been doing
it with standard themes.

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

    <themes>
        <virtual-theme name="foo">
            <themes>
                <ref theme="foo_desktop" />
                <ref theme="foo_mobile" />
            </themes>
            <tags>
                <tag name="jungi.desktop_devices" />
                <tag name="jungi.mobile_devices" />
                <tag name="jungi.tablet_devices" />
            </tags>
        </virtual-theme>
        <theme name="foo_desktop" path="@JungiFooBundle/Resources/theme/desktop">
            <tags>
                <tag name="jungi.desktop_devices" />
            </tags>
        </theme>
        <theme name="foo_mobile" path="@JungiFooBundle/Resources/theme/mobile">
            <tags>
                <tag name="jungi.mobile_devices" />
                <tag name="jungi.tablet_devices" />
            </tags>
        </theme>
    </themes>
    
</theme-mapping>
```

### YAML

```yml
# FooBundle/Resources/config/theme.yml
themes:
    foo:
        is_virtual: true
        themes: [ foo_desktop, foo_mobile ]
        tags:
            jungi.desktop_devices: ~
            jungi.mobile_devices: ~
            jungi.tablet_devices: ~
    foo_desktop:
        path: "@JungiFooBundle/Resources/theme/desktop"
        tags:
            jungi.desktop_devices: ~
    foo_mobile:
        path: "@JungiFooBundle/Resources/theme/mobile"
        tags:
            jungi.mobile_devices: ~
            jungi.tablet_devices: ~

```

### PHP

```php
<?php
// FooBundle/Resources/config/theme.php

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

$desktop = new Theme(
    'foo_desktop',
    $locator->locate('@JungiFooBundle/Resources/theme/desktop'),
    new TagCollection(array(
        new Tag\DesktopDevices(),
    ))
));
$mobile = new Theme(
    'foo_mobile',
    $locator->locate('@JungiFooBundle/Resources/theme/mobile'),
    new TagCollection(array(
        new Tag\MobileDevices(),
        new Tag\TabletDevices()
    ))
));

$collection = new ThemeCollection();
$collection->add(new VirtualTheme(
    'foo',
    array($desktop, $mobile),
    new TagCollection(array(
        new Tag\DesktopDevices(),
        new Tag\MobileDevices(),
        new Tag\TabletDevices()
    ))
));

return $collection;
```

Summary
-------

As a final step we must set up the virtual theme name `foo` to the theme resolver.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)