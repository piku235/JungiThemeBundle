Themes overview
===============

Here I have described each worthy thing that can interest you. Generally this chapter speaks about internal things located
in the JungiThemeBundle like e.g. template locations.

Theme locations
---------------

Just like I said in the root of documentation a theme is located in a bundle. Your job is to recognize when to use
multiple themes in a single bundle and when to decouple these themes to separate bundles.

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

You can override every bundle template that you wish in your theme, that's the main goal of themes. Suppose that e.g.
**FooThemeBundle** is the current theme for the request, and the **Default** controller with the **index** action
from the **BooBundle** will be performed.

The BooBundle:

```
BooBundle/
    Resources/
        Default/
            index.html.twig
        layout.html.twig
```

And the FooThemeBundle:

```
FooThemeBundle/
    Resources/
        themes/
            exclusive/
                BooBundle/
                    layout.html.twig
```

In this example the **FooThemeBundle** has overwritten the template `layout.html.twig` of the **BooBundle**. When the
template **index.html.twig** is rendered the template `layout.html.twig` of the **FooThemeBundle** is included instead
of the template `layout.html.twig` from the **BooBundle**.

Theme
-----

[Show the interface](https://github.com/piku235/JungiThemeBundle/blob/master/Core/ThemeInterface.php)

Each theme is an instance of the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. Thanks to this interface we can easily
manipulate themes and obtain important for us information.

### Default implementation

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Core/Theme.php)

The `Jungi\Bundle\ThemeBundle\Core\Theme` is the default theme implementation and it only defines basic methods contained
in the interface. It's used by the XmlFileLoader and the YamlFileLoader. Properties of this class can be only set by its
constructor.

The snippet of the constructor:

```php
namespace Jungi\Bundle\ThemeBundle\Core;

/**
 * Constructor
 *
 * @param string $name An unique theme name
 * @param string $path A path to theme resources
 * @param DetailsInterface $details A details
 * @param TagCollection $tags A tag collection (optional)
 */
public function __construct($name, $path, DetailsInterface $details, TagCollection $tags = null);
```

Details
-------

[Show the interface](https://github.com/piku235/JungiThemeBundle/blob/master/Core/DetailsInterface.php)

Each theme must contain an instance of the `Jungi\Bundle\ThemeBundle\Core\DetailsInterface`. It give us useful information
about a theme.

**NOTE**

> As you have noticed the two methods: **getName**, **getVersion** of the details interface should always return a value,
> so that means they're required.

### Default implementation

The `Jungi\Bundle\ThemeBundle\Core\Details` is the default details implementation. In comparison with the default theme
implementation it doesn't use the constructor like in the default theme implementation, because it would bring a mess in its
constructor signature.

The constructor of class takes only one argument `$parameters` which is of array type. This array should consist of keys
from the table below.

Key | Required
--- | --------
name | true
version | true
description | false
license | false
thumbnail | false
author.name | false
author.email | false
author.site | false

#### Example

```php
$details = new Details(array(
    'name' => 'foo',
    'version' => '1.0.0',
    'description' => 'awesome theme',
    'license' => 'MIT',
    'thumbnail' => 'a location',
    'author.name' => 'foo_author',
    'author.email' => 'foo_email',
    'author.site' => 'foo_site'
));
```
