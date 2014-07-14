Documentation
=============

This documentation was created for the master version of the bundle.

Getting Started
---------------

Here you will find out how to use and set up the bundle.

### Installation

To use the bundle in your symfony project [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/installation.md).

### Configuration

To set up the bundle just like you want [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/configuration.md).

Basics
------

### What exactly is a theme?

The theme is an ordinary object which implements `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. A theme lives in a bundle
and you can have as many themes as you want in the bundle.

### Themes overview

I have explained here many important details about themes e.g. template locations, a details class of the theme and so on.
If your are interested [click here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md).

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
facilitate many things which occurs at the frontend side. For example generating an image which will fit dimensions of
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