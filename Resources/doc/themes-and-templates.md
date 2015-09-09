Themes and templates
====================

This chapter thoroughly explains how template files from themes are selected and where themes are located, so read this
chapter carefully.

Themes locations
----------------

You have two possible theme locations for choose: bundle or project root.

### Bundle

Generally themes are living in a bundle. There is no limit saying how many themes you can have in a single bundle. You 
must only decide when these themes should be together and when they should be divided into single bundles.

Most of cases themes will be very simple, limited to have only some web assets and templates, so you may think "Is 
a bundle really a good place for themes?". Think about more advanced themes like i.e. themes that have some kind of web 
configurator with user interface, themes that connects with a vendor via a specific web service to do something cool and 
so on. As you see bundle is a perfect place due to its portability, the straightforward structure and enormous possibilities.

Let's consider a situation when you have three themes, where only two of them are related in some way and the third one 
is whole different (different logic or maybe different graphics). For example you can create the first bundle **FooBundle** 
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

**IMPORTANT**

> You must remember that putting your themes with templates is not enough for the JungiThemeBundle to notice these 
> new added themes. To make a theme(s) visible you will have to use one of the available theme mappings which are
> described [here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/index.md#theme-mappings). 

Template naming and locations
-----------------------------

The template naming is just the same as the symfony template naming conventions, so there is still the same syntax 
`bundle:controller:template` for templates. The only difference are locations of templates.

Suppose that we want to render e.g `FooBundle:Default:index.html.twig`:

1. The template name will be searched in the current theme resources and if the corresponding template file exists then 
this file will be used.
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
is the current theme, and the `Default/index.html.twig` template from the **BooBundle** will be rendered.

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

In this example the theme **exclusive** has overridden the template `layout.html.twig` of the **BooBundle**. When the
template `index.html.twig` is rendered the template `layout.html.twig` of the theme **exclusive** is included instead
of the template `layout.html.twig` from the **BooBundle**.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
