Theme tags
==========

The goal of the JungiThemeBundle are the mostly theme tags. They can be used for various things like searching and 
grouping themes. They are mainly used in adaptive themes ([Adaptive Web Design](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md)).

Each theme tag is a class that implements the `Jungi\Bundle\ThemeBundle\Tag\TagInterface`.

```php
interface TagInterface
{
    /**
     * Checks if the given tag is equal.
     *
     * @param TagInterface $tag A tag
     *
     * @return bool
     */
    public function isEqual(TagInterface $tag);

    /**
     * Gets the tag name.
     *
     * The returned name should be in the following notation: "vendor.tag_type" e.g. "jungi.mobile_devices".
     * This notation prevents from replacing tags by different vendors
     *
     * @return string
     */
    public static function getName();
}
```

Built-in tags
-------------

The bundle comes with three built-in tags. All tags are located under the `Jungi\Bundle\ThemeBundle\Tag` namespace.

Class | Name
----- | ----
MobileDevices | jungi.mobile_devices
TabletDevices | jungi.tablet_devices
DesktopDevices | jungi.desktop_devices

### MobileDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/MobileDevices.php)

The MobileDevices tag as you can guess is intended for themes that supports mobile devices (excl. tablet devices). Moreover
you can specify mobile operating systems that a theme will handle, e.g. you can have a theme which is only designed for 
AndroidOS and iOS or for any other mobile system.

#### The snippet of the constructor:

```php
/**
 * Constructor
 *
 * @param string|array $systems Operating systems (optional)
 *  Operating systems should be the same as in the MobileDetect class
 */
public function __construct($systems = array());
```

For the `$systems` argument you can provide e.g. iOS, AndroidOS, WindowsPhoneOS, etc. or leave it empty which means that
all operating systems will be matched. The supported operating systems are listed in the [MobileDetect](https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.php)
class under the variable `$operatingSystems`.

### TabletDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/TabletDevices.php)

The **TabletDevices** tag is just the same as the **MobileDevices** tag, expect that is intended for themes that supports 
tablet devices. It accepts the same arguments as the **MobileDevices** tag.

### DesktopDevices

[Show the class](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/DesktopDevices.php)

The **DesktopDevices** tag is one of the smallest tags, because it implements only basic methods contained in the interface. 
Each theme designed for desktop devices (the most likely scenario) should have this tag.

Usage
-----

All the above tags are used in responsive themes ([RWD](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/rwd.md))
and in adaptive themes ([AWD](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md)). Of course
tags are not limited only to doing that kind of things, you can create your own tag which will be designed for other 
purposes.

If you are curious how exactly tags works, you can see it by looking into unit tests [here](https://github.com/piku235/JungiThemeBundle/blob/master/Tests/Resolver/Filter/DeviceThemeFilterTest.php)
and [here](https://github.com/piku235/JungiThemeBundle/blob/master/Tests/Resolver/VirtualThemeResolverTest.php).

Creating tag
------------

Tags are pretty straightforward due to the small and compact interface. Here is the simplest tag that could be created:

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

As you see there is not required to write a lot of code to get a proper working tag. Of course the tag above does not do
anything special, but if you want you can write more complex tags.

### Register created tag

After you created a tag you will need to register it in order to use it in a particular theme mapping. To do this follow 
steps located in the section below.

Tag registry
------------

[Show the interface](https://github.com/piku235/JungiThemeBundle/tree/master/Tag/Registry/TagRegistryInterface.php)

A tag registry is a place where you can obtain a fully qualified class name of a tag by passing only its name. The solely 
purpose of a tag registry is an ability of registering new tags - thanks to that in theme mapping files you are able to 
not only use the built-in tags, but also to use tags created by you. :)

There are basically two ways of registering tags. 

### Register via JungiThemeExtension

```php
<?php
// src/Jungi/FooBundle/JungiFooBundle.php
namespace Jungi\FooBundle;

use Jungi\Bundle\ThemeBundle\DependencyInjection\JungiThemeExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * JungiFooBundle
 */
class JungiFooBundle extends Bundle
{
	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $builder)
	{
	    $ext = $builder->getExtension('jungi_theme');
        $ext->registerTag('Jungi\Bundle\ThemeBundle\Tag\FooTag');
	}
}
```

### Register via configuration

```yml
# app/config/config.yml
jungi_theme:
    tags:
        - Jungi\Bundle\ThemeBundle\Tag\FooTag
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
