Theme overview
==============

This chapter explains basic elements located in the theme.

Theme
-----

As mentioned in the main documentation the theme is an ordinary object which implements the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`.
And here I will concentrate on explaining each of the interface method.

Snapshot of the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`:

```php
<?php
namespace Jungi\Bundle\ThemeBundle\Core;

/**
 * The basic interface which themes must implement
 */
interface ThemeInterface
{
    /**
     * Returns the theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the theme tag collection
     *
     * @return \Jungi\Bundle\ThemeBundle\Tag\Core\TagCollection
     */
    public function getTags();

    /**
     * Returns the absolute path to a theme
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the details of the theme
     *
     * @return DetailsInterface
     */
    public function getDetails();
}
```

* **getName** - Returns a unique name of the theme. Themes with the same name cannot exist together.
* **getTags** - Must return a `Jungi\Bundle\ThemeBundle\Tag\Core\TagCollection` object even if the theme hasn't got any tags.
In this case the tag collection can be empty. If you wish to know more about theme tags [click here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-tags.md).
* **getPath** - Returns an absolute path to the theme resources. Generally this path points to a bundle resources e.g.
/absolute/path/to/src/Boo/FooBundle/Resources/theme.
* **getDetails** - Returns a `Jungi\Bundle\ThemeBundle\Core\DetailsInterface` instance. The details are explained in the next
[section](#details).

### Implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/Theme.php)

The `Jungi\Bundle\ThemeBundle\Core\Theme` is a good example of the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface` implementation.
Properties of an object can be only set by the constructor due to the encapsulation benefits. I guess that no one wants
to change property of a theme after it has been created. The class is used by e.g. XmlFileLoader, YamlFileLoader.

Details
-------

The purpose of details in a theme is mainly to provide some useful information for a user. Here you can set e.g.
a display name of the theme.

```php
<?php
namespace Jungi\Bundle\ThemeBundle\Core;

/**
 * DetailsInterface
 */
interface DetailsInterface
{
    /**
     * Returns the friendly theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the author
     *
     * @return string|null
     */
    public function getAuthor();

    /**
     * Returns the author mail
     *
     * @return string|null
     */
    public function getAuthorMail();

    /**
     * Returns the author site
     *
     * @return string|null
     */
    public function getAuthorSite();

    /**
     * Returns the version
     *
     * @return string
     */
    public function getVersion();

    /**
     * Returns the description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Returns the type of license
     *
     * @return string|null
     */
    public function getLicense();
}
```

I think all methods are pretty understandable. The details class acts as a representative of the theme, nothing more.

**WARNING**

> I want to mark that the Details class of a theme should at least return a friendly name and a version of this theme.

### Implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/Details.php)

The `Jungi\Bundle\ThemeBundle\Core\Details` is a simple implementation of the `Jungi\Bundle\ThemeBundle\Core\DetailsInterface`. This
class is similar to the Theme implementation, because it uses also the constructor for setting the properties.