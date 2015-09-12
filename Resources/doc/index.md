Documentation
=============

This documentation was created for the master version of the bundle.

Installation
------------

To get start using the bundle in your project [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/installation.md).

Basics
------

### Fundamental elements

Here you will get familiar with basic elements of the bundle like e.g. standard and virtual theme.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/fundamental-elements.md)

### Themes and templates

Theme locations, how to override a bundle template by theme and even more you will find out from reading this chapter. 

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-and-templates.md)

### Theme mappings

To start your adventure and create your first theme you will have to use one of the following theme mappings:

* [XML](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/xml-theme-mapping.md)
* [YAML](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/yaml-theme-mapping.md)
* [PHP](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/php-theme-mapping.md)

How to load a theme mapping file, you will find out from [here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md).

### Theme holder

A theme holder is a class that holds the current theme instance. To get the current theme you must use the service `jungi_theme.holder`
and call its method **getTheme** like below.

```php
// the current theme
$theme = $container->get('jungi_theme.holder')->getTheme();
```

More details about a theme holder you can read [here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-holder.md).

### Theme tags

Here you will find out what exactly is a theme tag, which theme tags the bundle has got and how to create them.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-tags.md)

### Theme changer

A theme changer is used for changing the current theme. [Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-changer.md)
to see how do that.

Tutorials
---------

### Creating a theme

The tutorial shows how to create a simple responsive theme (RWD) from scratch.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/tutorials/creating-theme.md)

Web Design Approaches
---------------------

There is a bulk of articles on the web speaking about web design approaches but generally you'll hit on these below.
I wanna show you how to use each of these approaches in the JungiThemeBundle.

### RESS (Responsive + Server-Side)

As you see in the title the RESS is nothing else than the RWD. The RESS additionally provides server components to
facilitate many things which occurs at a frontend side. For example generating an image which will fit dimensions of
a mobile device display gives us the reduction of bandwidth and faster page execution. You can learn more from this
[article](http://www.lukew.com/ff/entry.asp?1392) written by Luke Wroblewski.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/ress.md)

### AWD (Adaptive Web Design)

Sometimes you can come to conclusion that you need a separate themes for desktop and mobile devices. For this case you can
use the Adaptive Web Design approach. The AWD is maybe not so frequent used like RWD but this approach has own advantages
where you can read about them in this great [article](http://www.lukew.com/ff/entry.asp?1562) written by Luke Wroblewski.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md)

### RWD (Responsive Web Design)

The Responsive Web Design is probably the most used web design approach, of course for those who wants display their
theme on various devices. Creating this kind of theme is very straightforward and it takes really small amount of time.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/rwd.md)

Miscellaneous
-------------

* [Theme resolver](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-resolver.md)
* [Theme selector](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-selector.md)
* [Virtual theme resolver](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/virtual-theme-resolver.md)
* [Configuration reference](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/configuration.md)
