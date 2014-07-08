Theme tags
==========

The goal of the JungiThemeBundle are the mostly tags. They takes the information role and can be used for searching and
grouping themes. I guess that you're thinking now: "Why they're so significant and why I should use them?" In this chapter
I'll try to answer to these questions.

Built-in tags
-------------

The JungiThemeBundle comes with three builtin tags: **MobileDevices**, **DesktopDevices** and **Link**. All tags are
located in the `Jungi\Bundle\ThemeBundle\Tag` namespace.

### MobileDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/MobileDevices.php)

The MobileDevices tag allows you to define mobile operating systems and a device type which will be supported by theme.
So e.g. there can be a theme which was designed only for AndroidOS, iOS or for all mobile systems. You can even
specify the device type (tablet, mobile).

#### The snippet of the constructor:

```php
/**
 * Constructor
 *
 * @param string|array $systems Operating systems (optional)
 *  Operating systems should be the same as in the MobileDetect class
 * @param int $deviceType A constant of device type (optional)
 */
public function __construct($systems = array(), $deviceType = self::ALL_DEVICES)
{
    // code
}
```

For the `$systems` argument you can provide e.g. iOS, AndroidOS, WindowsPhoneOS, etc. or leave it empty which means
that all operating systems will be matched. The full list of supported operating systems you can find
[here](https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.json) under "os" entry.

For the `$deviceType` argument you have three constants:

Constant | Device
-------- | ------
MobileDevices::MOBILE | mobile
MobileDevices::TABLET | tablet
MobileDevices::ALL | all

### DesktopDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/DesktopDevices.php)

The DesktopDevices is a very basic tag (empty) and it doesn't implement any additional methods. Each theme designed for
desktop devices (the most likely scenario) should have this tag.

### Link

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/Link.php)

The aim of the Link tag is to be a pointer to another theme. It can be only used with the one from the above tags. It's
mainly used in situations such as when you have two separate themes where each one is designed for another device
([Adaptive Web Design](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md#awd-adaptive-web-design)).

#### The snippet of the constructor:

```php
/**
 * Constructor
 *
 * @param string $theme A pointed theme name
 */
public function __construct($theme)
{
    // code
}
```

As you see the constructor takes only one argument `$theme` which has a theme name.

Usage examples
--------------

How tags works you can see by looking into unit tests [here](https://github.com/piku235/JungiThemeBundle/tree/master/Tests/Selector/EventListener/DeviceThemeSwitchTest.php)
and [here](https://github.com/piku235/JungiThemeBundle/tree/master/Tests/Selector/StandardThemeSelectorTest.php).

Also you have two example bundles which I mentioned in the **README.md** of the root directory, but I will mention them
again if you haven't seen them yet:

* [JungiSimpleEnvironmentBundle](https://github.com/piku235/JungiSimpleEnvironmentBundle) - this bundle defines own
environment system where each environment may use different themes. So e.g. admin environment may has a theme "foo_admin"
and default environment may has a theme "foo_default".
* [JungiSimpleThemeBundle](https://github.com/piku235/JungiSimpleThemeBundle) - this bundle has the definition of a single
theme which uses the Environment tag located in the JungiSimpleEnvironmentBundle.

Creating own tag
----------------

[Show the TagInterface](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/TagInterface.php)

Tags are pretty straightforward so there is no problem with creating them. The tag class must implement `Jungi\Bundle\ThemeBundle\Tag\TagInterface`
and its two methods:

* **isEqual** - This is the crucial method which decides about matching tags
* **getName** - Returns the unique tag name in the following format "vendor.tag_name" e.g. "jungi.mobile_devices".
Thereby tags created by different vendors will not override each other.

### Register a tag

After you created your tag you will have to register it to use it e.g. in theme mappings. To do this follow the
instructions located in the "Tag Registry" section.

Tag Registry
------------

[Show the interface](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/Registry/TagRegistryInterface.php)

The tag registry is a place where you can obtain a path to the class of a tag by passing tag name. The main goal of the
tag registry is an ability about registering new tags. Thanks to that theme mapping loaders are able to not only use the
built-in tags, but additionally to use the tags created e.g. by you (:

### How to register a new tag?

The registration can be done from the symfony services which is very comfortable way. The only thing you have to do is to
define a service with the tag `jungi.tag_provider`. As a service class you will use the parameter `jungi.theme.tag.registry.provider.class`
which by default points to the class `Jungi\Bundle\ThemeBundle\Tag\Registry\TagProvider`. The tag provider only provides
tags to a tag registry and nothing more.

#### Example

##### XML

```xml
<parameters>
    <parameter key="foo.theme.tag.class">Foo\FooBundle\Theme\Tag\BooTag</parameter>
    <!-- other parameters -->
</parameters>

<services>
    <service id="foo.theme.tag.provider" class="%jungi.theme.tag.registry.provider.class%">
        <tag name="jungi.tag_provider" />
        <argument>%foo.theme.tag.class%</argument>
    </service>
    <!-- other services -->
</services>
```

##### YAML

```yml
parameters:
    foo.theme.tag.class: Foo\FooBundle\Theme\Tag\BooTag

services:
    foo.theme.tag.provider:
        class: %jungi.theme.tag.registry.provider.class%"
        tags:
            -  { name: jungi.tag_provider }
        arguments: ["%foo.theme.tag.class%"]
```
