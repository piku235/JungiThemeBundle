Themes overview
===============

Here I have described each worthy thing that can interest you. Generally this chapter speaks about internal things located
in the JungiThemeBundle like e.g. template locations.

Theme locations
---------------

Generally themes are staying in a bundle. There is no limit saying how many themes you can have in a single bundle. You
must only decide when these themes should be together and when they should be separated into single bundles.

Consider the situation when you have three themes, where only two of them are related in some way and the third one is
whole different (different logic or maybe different graphics). You can create a first bundle e.g. **FooBundle** for these
two related themes and create a second bundle e.g. **BooBundle** for the third theme.

The directory structure could looks like below:

```
FooBundle/
    Resources/
        themes/
            first_related/
            second_related/

BooBundle
    Resources/
        themes/
            third
```

Template naming and locations
-----------------------------

The template naming is just the same as the symfony template naming conventions, so you still have the syntax `bundle:controller:template`
for templates. The only difference are locations of templates.

Suppose that we want to render e.g `FooBundle:Default:index.html.twig`:

1. The template name will be searched in the current theme resources and if the given template name exists then this found
template resource will be used.
2. If the given template name can not be found in the current theme resources then the default search process (just like
the symfony does) will be performed, so finally a template resource from the **FooBundle** will be used.

### Template syntax

Name | Path
---- | ----
FooBundle:Default:index.html.twig | /path/to/themebundle/Resources/themes/foo/FooBundle/Default/index.html.twig
FooBundle::layout.html.twig | /path/to/themebundle/Resources/themes/foo/FooBundle/layout.html.twig
::master.html.php | /path/to/themebundle/Resources/themes/foo/master.html.php

Overriding bundle templates
---------------------------

You can override every bundle template that you wish in your theme. Suppose that the theme **exclusive** of the **FooThemeBundle**
is the current theme for the request, and the **Default** controller with the **index** action from the **BooBundle**
will be performed.

The BooBundle resources:

```
BooBundle/
    Resources/
        Default/
            index.html.twig
        layout.html.twig
```

And the simple content of the `index.html.twig`:

```php
{% extends 'BooBundle::layout.html.twig' %}

{% block content %}
<p>Lorem ipsum.</p>
{% endblock %}
```

The FooThemeBundle resources:

```
FooThemeBundle/
    Resources/
        themes/
            exclusive/
                BooBundle/
                    layout.html.twig
```

In this example the theme **exclusive** has overwritten the template `layout.html.twig` of the **BooBundle**. When the
template `index.html.twig` is rendered the template `layout.html.twig` of the theme **exclusive** is included instead
of the template `layout.html.twig` from the **BooBundle**.

Theme
-----

Each theme is an instance of the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. Thanks to this interface we can easily
manipulate themes and obtain important for us information.

```php
interface ThemeInterface
{
    /**
     * Returns the unique theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the theme tag collection
     *
     * @return \Jungi\Bundle\ThemeBundle\Tag\TagCollection
     */
    public function getTags();

    /**
     * Returns the absolute path to the theme directory
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the details of the theme
     *
     * @return \Jungi\Bundle\ThemeBundle\Details\DetailsInterface
     */
    public function getDetails();
}
```

### Default implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/Theme.php)

The `Jungi\Bundle\ThemeBundle\Core\Theme` is the default theme implementation and it only defines basic methods contained
in the interface. All properties of the class can be only set by its constructor. You can use this class e.g. in php
theme mapping files.

Details
-------

The Details is a class which describes a theme. It can give us some important information about a theme such as a theme
name, theme version and etc. Each details class must implement the `Jungi\Bundle\ThemeBundle\Details\DetailsInterface`.

```php
interface DetailsInterface
{
    /**
     * Returns the friendly theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the version
     *
     * @return string
     */
    public function getVersion();

    /**
     * Returns the authors
     *
     * @return AuthorInterface[]
     */
    public function getAuthors();

    /**
     * Returns the description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Returns the license type
     *
     * @return string|null
     */
    public function getLicense();
}
```

**NOTE**

> The two methods: **getName**, **getVersion** of the interface should always return a value

### Default implementation

The `Jungi\Bundle\ThemeBundle\Details\Details` is the default details implementation. It's a little bit different from the
default theme implementation. Due to a large number of the properties I decided to not implement the constructor,
because it would only bring a mess in its signature. Also setter methods are not a good idea, because after
an object creation there still will be a possibility for changing an object properties and that shouldn't be possible.
Finally I came to conclusion to create the simple builder `Jungi\Bundle\ThemeBundle\Details\DetailsBuilder` which is strictly
associated with the class `Jungi\Bundle\ThemeBundle\Details\Details`. The builder provides setter methods with the fluent
interface support.

Here is an example of creating a new details instance:

```php
use Jungi\Bundle\ThemeBundle\Details\Details;
use Jungi\Bundle\ThemeBundle\Details\Author;

$dsb = Details::createBuilder();
$dsb
    ->setName('A simple theme')
    ->setVersion('1.0.0')
    ->setDescription('a simple theme with the beautiful design')
    ->setLicense('GPL')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'foo.com'))
;

// Builds the new Details instance
$details = $dsb->getDetails();
```

