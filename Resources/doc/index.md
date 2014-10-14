Documentation
=============

This documentation was created for the master version of the bundle.

Getting Started
---------------

Here you will find out how to use and set up the bundle.

### Installation

To start using the bundle in your symfony project [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/installation.md).

### Configuration

If you wish to know more about the bundle configuration [click here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/configuration.md).

Basics
------

### What exactly is a theme?

A theme is class which implements the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. Basically themes lives in bundles
and you can have in a single bundle one or multiple themes. When to use multiple themes in a single bundle you can read
in the **Themes overview** chapter.

### How to get the current theme?

The current theme is stored under a theme holder. This theme holder is a class which implements the `Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface`.
To get the current theme you must use the service `jungi_theme.holder` and call its method **getTheme**. The class
`Jungi\Bundle\ThemeBundle\Core\SimpleThemeHolder` is the default theme holder and you can change it at the [configuration](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/configuration.md)

### Themes overview

The chapter contains many important things about themes e.g. template locations, overriding bundle templates. If you're
interested [click here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md).

### Theme mappings

To start your adventure and create your first theme you will have to use one of the following theme mappings:

* [XML](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/xml-theme-mapping.md)
* [YAML](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/yaml-theme-mapping.md)
* [PHP](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/php-theme-mapping.md)

How to load a theme mapping file, you will learn from [here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mappings.md).

### Theme tags

Theme tags are the main goal of the JungiThemeBundle. They takes the information role and can be used for searching and
grouping themes. In this chapter you will also learn how to register new tags.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-tags.md)

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
theme on various devices. Creating a responsive theme in the JungiThemeBundle is very easy and fast.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/rwd.md)