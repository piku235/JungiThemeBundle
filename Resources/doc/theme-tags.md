Theme tags
==========

The goal of the JungiThemeBundle are the mostly tags. They takes the information role and can be used for searching and
grouping themes. They are mandatory for adaptive themes ([Adaptive Web Design](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md))
and they should be used in each theme but they are not required.

Built-in tags
-------------

The bundle comes with three built-in tags. All tags are located under the `Jungi\Bundle\ThemeBundle\Tag` namespace.

Class | Name
----- | ----
MobileDevices | jungi.mobile_devices
DesktopDevices | jungi.desktop_devices
VirtualTheme | jungi.virtual_theme

### MobileDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/MobileDevices.php)

The MobileDevices tag allows you to define mobile operating systems and a device type which will be supported by theme.
So e.g. there can be a theme which was designed only for AndroidOS, iOS or for all mobile systems. You can even specify
the device type (tablet, mobile).

#### The snippet of the constructor:

```php
/**
 * Constructor
 *
 * @param string|array $systems Operating systems (optional)
 *  Operating systems should be the same as in the MobileDetect class
 * @param int $deviceType A constant of device type (optional)
 */
public function __construct($systems = array(), $deviceType = self::ALL_DEVICES);
```

For the `$systems` argument you can provide e.g. iOS, AndroidOS, WindowsPhoneOS, etc. or leave it empty which means that
all operating systems will be matched. The supported operating systems are listed in the [MobileDetect](https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.php)
class under `$operatingSystems` variable.

For the `$deviceType` argument you have three constants:

Constant | Device
-------- | ------
MobileDevices::MOBILE | mobile
MobileDevices::TABLET | tablet
MobileDevices::ALL | all

### DesktopDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/DesktopDevices.php)

The DesktopDevices is a very basic tag and it implements only basic methods contained in the interface. Each theme designed
for desktop devices (the most likely scenario) should have this tag.

### VirtualTheme

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/VirtualTheme.php)

The aim of the VirtualTheme tag is to connect multiple themes into one. It can be done by implementing this tag to desired
themes through specifying the same virtual theme name. It's mainly used in the [AWD](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md#awd-adaptive-web-design) 
(Adaptive Web Design), but it can be also used for any other purpose. More details about virtual themes you'll find in 
the [Theme matcher](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-matcher.md) chapter.

Usage
-----

The usage of each tags depends on a theme mapping that you'll choose. For example the **MobileDevices** tag and the 
**DesktopDevices** tag were used in the [RWD](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/rwd.md) 
chapter and also they were used with the **VirtualTheme** in the [AWD](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md) chapter.

If you're curious how exactly tags works, you can see it by looking into unit tests [here](https://github.com/piku235/JungiThemeBundle/blob/master/Tests/Matcher/Filter/DeviceThemeFilterTest.php)
and [here](https://github.com/piku235/JungiThemeBundle/blob/master/Tests/Matcher/VirtualThemeMatcherTest.php).

Creating tag
------------

Each tag is a class which implements the `Jungi\Bundle\ThemeBundle\Tag\TagInterface`:

```php
interface TagInterface
{
    /**
     * Checks if a given tag is equal
     *
     * @param TagInterface $tag A tag
     *
     * @return bool
     */
    public function isEqual(TagInterface $tag);

    /**
     * Gets the tag name
     *
     * The returned name should be in the following notation: "vendor.tag_type" e.g. "jungi.mobile_devices".
     * This notation prevents from replacing tags by different vendors
     *
     * @return string
     */
    public static function getName();
}
```

Tags are pretty straightforward due to the small API. Here is the simplest tag that can be created:

```php
use Jungi\Bundle\ThemeBundle\Tag\TagInterface;

class SimpleTag extends TagInterface
{
    public function isEqual(TagInterface $tag)
    {
        return true;
    }

    public static function getName()
    {
        return 'jungi.simple_tag';
    }
}
```

As you see there isn't required to write a lot of code to get a proper working tag. Of course the tag above doesn't do
anything special, but you can write more complex tags.

### Register created tag

After you created a tag you will need to register it in order to use it e.g. in a theme mapping document. To do this follow 
the instructions located in the **Tag Registry** section.

Tag registry
------------

[Show the interface](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/Registry/TagRegistryInterface.php)

A tag registry is a place where you can obtain a full qualified class name of tag by passing only a tag name. The main 
goal of a tag registry is an ability about registering new tags. Thanks to that theme mapping loaders are able to not only 
use the built-in tags, but also to use tags created by you :)

### How to register a new tag?

The registration of a new tag can be done from the symfony services which is a very comfortable way. The only thing you 
have to do is to define a service with the tag `jungi_theme.tag_provider`. As a service class you can use the parameter 
`jungi_theme.tag.registry.provider.class` which by default points to the `Jungi\Bundle\ThemeBundle\Tag\Registry\TagProvider`
class. A tag provider only delivers tags to a tag registry and nothing more.

#### XML Service

```xml
<parameters>
    <parameter key="foo_vendor.theme.tag.class">Foo\FooBundle\Theme\Tag\BooTag</parameter>
    <!-- other parameters -->
</parameters>

<services>
    <service id="foo_vendor.theme.tag.provider" class="%jungi_theme.tag.registry.provider.class%">
        <tag name="jungi_theme.tag_provider" />
        <argument>%foo_vendor.theme.tag.class%</argument>
    </service>
    <!-- other services -->
</services>
```

#### YAML Service

```yml
parameters:
    foo_vendor.theme.tag.class: Foo\FooBundle\Theme\Tag\BooTag

services:
    foo_vendor.theme.tag.provider:
        class: "%jungi_theme.tag.registry.provider.class%"
        tags:
            - { name: jungi_theme.tag_provider }
        arguments: ["%foo_vendor.theme.tag.class%"]
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
