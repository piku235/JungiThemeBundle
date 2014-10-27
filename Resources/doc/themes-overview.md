Themes overview
===============

Theme
-----

Typically a theme as we know is a collection of some resources like images, stylesheets, javascripts which as a result 
have an influence to the look of a page. A theme representation in the JungiThemeBundle is a class which implements the 
`Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. Thanks to this interface we can easily manipulate themes and obtain 
important for us information.

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
     * Returns the information about the theme
     *
     * @return \Jungi\Bundle\ThemeBundle\Information\ThemeInfo
     */
    public function getInformation();
}
```

**INFO**

> To create a new theme you will have to use one of the available theme mappings: xml, yaml or php. Go [here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/index.md#theme-mappings)
> to know how do that.

### Default implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/Theme.php)

The `Jungi\Bundle\ThemeBundle\Core\Theme` is the default theme implementation and it only defines basic methods contained
in the interface. This class is useful when you want to create a standard theme e.g. in a php theme mapping file.

ThemeInfo
---------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Information/ThemeInfo.php)

In some cases you'd like to get some information about a theme in order to show these information to a user who would 
like to use that theme. Information can be easily stored in a class. In the JungiThemeBundle such a class must implement 
the `Jungi\Bundle\ThemeBundle\Information\ThemeInfo` abstract class. This abstract class is used as the interface and it
provides the most important getter methods.

```php
abstract class ThemeInfo
{
    /**
     * Returns the friendly theme name
     *
     * @return string
     */
    public function getName()
    {
        // code
    }

    /**
     * Returns the authors
     *
     * @return AuthorInterface[]
     */
    public function getAuthors()
    {
        // code
    }

    /**
     * Returns the version
     *
     * @return string|null
     */
    public function getVersion()
    {
        // code
    }

    /**
     * Returns the description
     *
     * @return string|null
     */
    public function getDescription()
    {
        // code
    }

    /**
     * Returns the license type
     *
     * @return string|null
     */
    public function getLicense()
    {
        // code
    }
}
```

**NOTE**

> The method **getName** of the ThemeInfo should always return a value

### ThemeInfoEssence

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Information/ThemeInfoEssence.php)

The `Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence` can be used as the default ThemeInfo implementation. It's a 
little bit different from the default theme implementation. Due to a large number of properties the implementation of the 
constructor seems to be a bad idea, because it would only bring a mess in the constructor signature. Also setter methods 
are not a good idea, because after an object creation there still will be a possibility for changing an object properties 
and that shouldn't be possible. Finally I came to conclusion to create the simple builder `Jungi\Bundle\ThemeBundle\Information\ThemeInfoBuilder` 
which is strictly associated with the **ThemeInfoEssence** class. The builder provides setter methods with the fluent 
interface support.

Here is an example of creating a new instance of the **ThemeInfoEssence**:

```php
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Information\Author;

$ib = ThemeInfoEssence::createBuilder();
$ib
    ->setName('A simple theme')
    ->setVersion('1.0.0')
    ->setDescription('a simple theme with the beautiful design')
    ->setLicense('GPL')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'foo.com'));

// Builds the new ThemeInfoEssence instance
$info = $ib->getThemeInfo();
```

Themes locations
----------------

Generally themes are staying in a bundle. There is no limit saying how many themes you can have in a single bundle. You
must only decide when these themes should be together and when they should be separated into single bundles.

Consider the situation when you have three themes, where only two of them are related in some way and the third one is
whole different (different logic or maybe different graphics). You can create a first bundle e.g. **FooBundle** for these
two related themes and create a second bundle e.g. **BooBundle** for the third theme.

The directory structure could looks like below:

```
FooBundle/
    Resources/
        theme/
            first_related/
            second_related/

BooBundle
    Resources/
        # third theme
        theme/
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
FooBundle:Default:index.html.twig | /path/to/theme/FooBundle/Default/index.html.twig
FooBundle::layout.html.twig | /path/to/theme/FooBundle/layout.html.twig
::master.html.php | /path/to/theme/master.html.php

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
        # exclusive theme
        theme/
            BooBundle/
                layout.html.twig
```

In this example the theme **exclusive** has overwritten the template `layout.html.twig` of the **BooBundle**. When the
template `index.html.twig` is rendered the template `layout.html.twig` of the theme **exclusive** is included instead
of the template `layout.html.twig` from the **BooBundle**.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)