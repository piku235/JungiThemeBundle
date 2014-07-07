Documentation
=============

The documentation describes everything what is located in the JungiThemeBundle.

Getting Started
---------------

Here you will find out how to use and set up the bundle.

### Installation

To use the bundle in your symfony project [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/installation.md).

### Configuration

To set up the bundle just like you want [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/configuration.md).

Basics
------

Here you will learn how to create and load themes and also you will know about them everything what you need.

### What exactly is a theme?

The theme is an ordinary object which implements `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. Themes resides in a bundle and
you can have in your bundle one or multiple themes.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-overview.md) to know more.

### When to use multiple themes in a bundle?

I recommend to use multiple themes in a bundle in the certain circumstances for e.g. when you have a theme for the desktop
devices and a second theme for the mobile devices, when these themes have the same part in a some point.

### Theme mappings

To create your own theme or themes you have for use these below theme mappings:

* [XML](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/xml-theme-mapping.md)
* [YAML](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/yaml-theme-mapping.md)
* [PHP](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/php-theme-mapping.md)

To see how to load your theme mapping file [go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mappings.md).

### Theme tags

Tags are the main goal of themes. They takes the information role and can be used for searching and grouping themes. Also
you will learn here how to register new tags.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-tags.md)

Web Design Approaches
---------------------

There is a bulk of articles on the web speaking about web design approaches but generally you'll hit on these below.
I wanna show you how to use each of these approaches in the JungiThemeBundle.

### RESS (Responsive + Server-Side)

As you see in the title the RESS is nothing else than the RWD. The RESS additionally provides server components to
facilitate many things which occurs at the frontend side. For example generating an image which will fit dimensions of
a mobile device display gives us the reduction of a bandwidth and a faster page execution. You can learn more from this
[article](http://www.lukew.com/ff/entry.asp?1392) written by Luke Wroblewski.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/ress.md)

### AWD (Adaptive Web Design)

Sometimes you can come to conclusion that you need a separate theme for desktop and mobile devices. For this you can use
the Adaptive Web Design approach. The AWD is maybe not so frequent used like RWD but this approach has own advantages where
you can read about them in this great [article](http://www.lukew.com/ff/entry.asp?1562) written by Luke Wroblewski.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/awd.md)

### RWD (Responsive Web Design)

The Responsive Web Design is probably the most used web design approach, of course for those who wants display their
theme on various devices. Creating a responsive theme in the JungiThemeBundle is very easy and fast.

[Go here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/rwd.md)