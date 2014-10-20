PHP Theme Mapping
=================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/PhpFileLoader.php)

Documents of this theme mapping are handled by the `Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpFileLoader`.

Prerequisites
-------------

Before you start I recommend to get familiar with the chapter [Theme Overview](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md)
to understand the further things located here.

Quick example
-------------

Here is the simple document which contains a single theme with basic elements:

```php
<?php
// FooBundle/Resources/config/theme.php
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Details\Details;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

$theme = new Theme(
    'footheme',
    $locator->locate('@JungiFooBundle/Resources/theme'),
    new Details(array(
        'name' => 'A fancy theme',
        'version' => '1.0.0',
        'description' => '<i>foo desc</i>',
        'license' => 'MIT',
        'author.name' => 'piku235',
        'author.email' => 'piku235@gmail.com',
        'author.site' => 'http://test.pl'
    )),
    new TagCollection(array(
        new Tag\DesktopDevices(),
        $tagFactory->create('jungi.mobile_devices', array(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE))
    ))
);
$manager->addTheme($theme);
```

Getting Started
---------------

Each document has access to these variables:

Variable | Class (default)
-------- | ---------------
$manager | Jungi\Bundle\ThemeBundle\Core\ThemeManager
$locator | Symfony\Component\HttpKernel\Config\FileLocator
$tagFactory | Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactory

As you see to add your theme instance you'll use the method `addTheme` of the `$manager` variable. You can add as many
theme instances as you want. Thanks to the `$locator` you can use paths to a bundle. The `$tagFactory` allows you to create
tags only by passing tag name and arguments for this tag. The simplest theme implementation which you can use is the
`Jungi\Bundle\ThemeBundle\Core\Theme` class which is described in the [Theme Overview](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md)
chapter.

Final
-----

Now if you have properly created your theme mapping file you can finally load it.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)