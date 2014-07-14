Themes overview
===============

Here I will concentrate on answering on typical questions: **"How looks loading of theme resources, How to override
bundle templates and so on"**.

Theme locations
---------------

Just like I said in the index of documentation a theme is located in a bundle. Your job is to recognize when to use
multiple themes in a single bundle and when to decouple these themes into single bundles.

Consider the situation when you have three themes, where only two of them are related in some way and the third one is
whole different (different logic or maybe different graphics). You can create a first bundle e.g. FooBundle for these two
related themes and for the third one create another bundle e.g. BooBundle.

Example directory structure:

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

Template naming is just the same as the symfony template naming, so you still have the syntax **bundle:controller:template**
for templates. The only difference are locations of templates. By first e.g **FooBundle:Default:index.html.twig** will
be searched in a current theme resources and if the given template name exists in the theme then this template resource
will be used. If the given template name can not be found in a current theme resources then the default search process
just like the symfony does will be performed, so finally a template resource from the **FooBundle** bundle will be used.

### Template syntax reference

Name | Path
---- | ----
FooBundle:Default:index.html.twig | /path/to/themebundle/Resources/themes/foo/FooBundle/Default/index.html.twig
FooBundle::layout.html.twig | /path/to/themebundle/Resources/themes/foo/FooBundle/layout.html.twig
::master.html.php | /path/to/themebundle/Resources/themes/foo/master.html.php

Overriding bundle templates
---------------------------

You can override every bundle templates that you wish in your theme. Suppose that the **FooThemeBundle** is the current
theme for a dispatched request, and the **Default** controller with the **index** action from the **BooBundle** will be
performed.

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

In this example the **FooThemeBundle** has overwritten the `layout.html.twig` of the **BooBundle**. When the **index.html.twig**
is rendered then the template `layout.html.twig` of the **FooThemeBundle** will be included instead of the `layout.html.twig`
from the **BooBundle**.
