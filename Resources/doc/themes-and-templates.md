Themes and templates
====================

Themes locations
----------------

You have two possible theme locations for choose: in a bundle or in a project root.

### Bundle

Generally themes are living in a bundle. There is no limit saying how many themes you can have in a single bundle. You 
must only decide when these themes should be together and when they should be separated into single bundles.

Most of cases themes will be very simple, limited to have only some web assets and view files, so you may think "Is 
a bundle really a good place for themes?". Think about more advanced themes like e.g. themes that have some kind of web 
configurator with user interface, themes that connects with a vendor via a specific web service to do something cool and 
so on. As you see a bundle is a perfect place due to its portability, the straightforward structure and enormous 
possibilities.

Consider a situation when you have three themes, where only two of them are related in some way and the third one is
whole different (different logic or maybe different graphics). For example you can create the first bundle **FooBundle** 
for these two related themes and create the second bundle **BooBundle** for the third theme.

The directory structure could looks like below:

```
FooBundle/
    Resources/
        themes/
            desktop/
            mobile/

BooBundle
    Resources/
        # third theme
        theme/
```

### Project root

You may also want to put your themes only for a particular project. Nothing easier!

A simple directory structure:

```
app/
    Resources/
        themes/
            desktop/
            mobile/
```

Template naming and locations
-----------------------------

The template naming is just the same as the symfony template naming conventions, so you still have the syntax `bundle:controller:template`
for templates. The only difference are locations of templates.

Suppose that we want to render e.g `FooBundle:Default:index.html.twig`:

1. The template name will be searched in the current theme resources and if the given template name exists then the found
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