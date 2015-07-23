Responsive Web Design (RWD)
===========================

To create a responsive theme you have only to choose an appropriate theme mapping and define inside it a theme with these
following tags:

* [MobileDevices](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#mobiledevices)
* [TabletDevices](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tabletdevices)
* [DesktopDevices](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#desktopdevices)

And that's all, thanks to these tags the JungiThemeBundle will notice that the theme will be used for mobile, tablet and 
desktop devices. Additionally you can use this advantage for seeking and obtaining an information about a theme.

**INFO**

> Even a responsive theme without these tags can be selected. These tags for a responsive theme has not got any special 
> meaning, so they are only used here to give us more detailed information about a theme which could be helpful while 
> presenting a theme to a user. 

Example
-------

I know that introduction can be insufficient, so I will demonstrate creating a responsive theme on the example.

### XML

```xml
<!-- FooBundle/Resources/config/theme.xml -->
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <parameters>
        <parameter key="mobile_systems" type="collection">
            <parameter>iOS</parameter>
            <parameter>AndroidOS</parameter>
        </parameter>
    </parameters>

    <themes>
        <theme name="foo" path="@JungiFooBundle/Resources/theme">
            <tags>
                <tag name="jungi.mobile_devices">%mobile_systems%</tag>
                <tag name="jungi.tablet_devices">%mobile_systems%</tag>
                <tag name="jungi.desktop_devices" />
            </tags>
        </theme>
    </themes>
    
</theme-mapping>

```

### YAML

```yml
# FooBundle/Resources/config/theme.yml
parameters:
    mobile_systems: [ iOS, AndroidOS ]

themes:
    foo:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            jungi.desktop_devices: ~
            jungi.mobile_devices: %mobile_systems%
            jungi.tablet_devices: %mobile_systems%

```

### PHP

```php
<?php
// FooBundle/Resources/config/theme.php

use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

$collection = new ThemeCollection();
$collection->add(new Theme(
    'foo',
    $locator->locate('@JungiFooBundle/Resources/theme'),
    new TagCollection(array(
        new Tag\DesktopDevices(),
        new Tag\MobileDevices(array('iOS', 'AndroidOS')),
        new Tag\TabletDevices(array('iOS', 'AndroidOS'))
    ))
));

return $collection;
```

Summary
-------

To see how your responsive theme works, load the created theme mapping file and set the theme name to the theme resolver.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
