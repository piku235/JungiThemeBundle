Theme Tags
==========

The goal of the JungiThemeBundle are the mostly tags. They takes the information role and can be used for searching and
grouping themes. They are mandatory for adaptive themes ([Adaptive Web Design](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md))
and they should be used in each theme but they are not required.

Built-in tags
-------------

The bundle comes with three built-in tags: **MobileDevices**, **DesktopDevices** and **Link**. All tags are located in
the `Jungi\Bundle\ThemeBundle\Tag` namespace.

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

### Link

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/Link.php)

The aim of the Link tag is to be a pointer to another theme. It can be only used with the one from the above tags. It's
mainly used in situations such as when you have two separate themes where each one is designed for another device and you
want to link them together ([Adaptive Web Design](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md#awd-adaptive-web-design)).

Usage examples
--------------

How tags works you can see by looking into unit tests [here](https://github.com/piku235/JungiThemeBundle/tree/master/Tests/Selector/EventListener/DeviceThemeSwitchTest.php)
and [here](https://github.com/piku235/JungiThemeBundle/tree/master/Tests/Selector/ThemeSelectorTest.php).

Also there are two example bundles which I mentioned in the **README.md** of the root directory, but I will mention them
again if you haven't seen them yet:

* [JungiSimpleEnvironmentBundle](https://github.com/piku235/JungiSimpleEnvironmentBundle) - this bundle defines own
environment system where each environment may use different themes. So e.g. admin environment may has a theme **foo_admin**
and default environment may has a theme **foo_default**.
* [JungiSimpleThemeBundle](https://github.com/piku235/JungiSimpleThemeBundle) - this bundle has a definition of two themes
which uses the Environment tag located in the JungiSimpleEnvironmentBundle.

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

Tags are pretty straightforward due to the lightweight API. Here is the simplest tag that can be created:

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

As you see there isn't required to write a lot of code to get proper working tag. Of course the tag above does not do
anything special, but you can write more complex tags.

### Register created tag

After you created a tag you will have to register it to use it e.g. in a theme mapping document. To do this follow the
instructions located in the **Tag Registry** section.

Tag registry
------------

[Show the interface](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/Registry/TagRegistryInterface.php)

A tag registry is a place where you can obtain the path to the class of tag by passing tag name. The main goal of a
tag registry is an ability about registering new tags. Thanks to that theme mapping loaders are able to not only use the
built-in tags, but additionally to use tags created e.g. by you :)

### How to register a new tag?

The registration can be done from the symfony services which is very comfortable. The only thing you have to do is to
define a service with the tag `jungi.tag_provider`. As a service class you can use the parameter `jungi_theme.tag.registry.provider.class`
which by default points to the `Jungi\Bundle\ThemeBundle\Tag\Registry\TagProvider`. A tag provider only provides tags to
a tag registry and nothing more.

#### XML Service

```xml
<parameters>
    <parameter key="foo.theme.tag.class">Foo\FooBundle\Theme\Tag\BooTag</parameter>
    <!-- other parameters -->
</parameters>

<services>
    <service id="foo.theme.tag.provider" class="%jungi_theme.tag.registry.provider.class%">
        <tag name="jungi.tag_provider" />
        <argument>%foo.theme.tag.class%</argument>
    </service>
    <!-- other services -->
</services>
```

#### YAML Service

```yml
parameters:
    foo.theme.tag.class: Foo\FooBundle\Theme\Tag\BooTag

services:
    foo.theme.tag.provider:
        class: "%jungi_theme.tag.registry.provider.class%"
        tags:
            - { name: jungi.tag_provider }
        arguments: ["%foo.theme.tag.class%"]
```
