YAML Theme Mapping
==================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/YamlFileLoader.php)

Documents of this theme mapping are handled by the **YamlFileLoader**. This like any other definition loader does 
not load themes right away.  

**IMPORTANT**

> There is one thing worthy to mention before you start. Everything in a theme mapping document has a local scope, so you 
> do not have to be afraid that something gets overridden. Themes at the beginning also have a local scope, only when they 
> are being added to a theme source they must have an unique name to prevent name conflicts.

Quick example
-------------

```yml
# FooBundle/Resources/config/theme.yml
parameters:
    mobile_devices: [ iOS, AndroidOS, WindowsPhoneOS ]
    authors: 
        - { name: piku235, email: piku235@gmail.com, homepage: www.foo.com }

themes:
    bar_adaptive:
        is_virtual: true
        themes:
            - { theme: bar_mobile, as: mobile }
            - { theme: dekstop }
        tags:
            jungi.desktop_devices: ~
            jungi.mobile_devices: [ "%mobile_devices%" ]
            jungi.tablet_devices: [ "%mobile_devices%" ]
        info:
            name: BarAdaptive
            authors:
                - { name: piku235, email: piku235@gmail.com, homepage: www.piku235.pl }
                - { name: foo, email: foo@gmail.com }
    
    bar_mobile:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            jungi.mobile_devices: [ "%mobile_devices%" ]
            jungi.tablet_devices: [ "%mobile_devices%" ]
                
    dekstop:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            jungi.desktop_devices: ~
                
    footheme:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            jungi.desktop_devices: ~
            jungi.mobile_devices: [ "%mobile_devices%" ]
            jungi.tablet_devices: [ "%mobile_devices%" ]
            jungi.environment: admin
        info:
            name: An awesome theme
            description: <i>foo desc</i>
            authors: %authors%
```

**NOTE**

> You can name your yaml document as you like, there is no any special requirement to name it theme.yml

Getting Started
---------------

OK, so let's start explaining from the top.

### Parameters

```yml
parameters:
    parameter_key: parameter_value
    # other parameters
```

Parameters can facilitate many things, especially when you have multiple theme definitions. They are exactly the same as 
parameters in the symfony services, expect that parameters in a theme mapping file has a local scope.

#### Constants

```yml
parameters:
    footheme.mobile_device: "const@jungi.fake::SPECIAL"
    footheme.mobile_device.global: "const@CONSTANT_BOO"
    footheme.mobile_device.full: "const@Jungi\Bundle\ThemeBundle\FooClass::MOBILE"
```

Additionally the support of constants was introduced. Like in the example above to call a constant you must only prepend 
it with the `const@` string. You can use a shortcut or a full qualified constant name. By the shortcut I mean the notation 
`tag_name::constant` e.g. `jungi.fake::SPECIAL` where it refers to a constant located in a tag. Naturally you can 
refer to global constants e.g. **SOME_CONSTANT** and to constants located in classes like in the example above.

#### Global parameters

To facilitate some things were introduced following global parameters:

Name | Description
---- | -----------
kernel.root_dir | parameter imported from the symfony service container, it returns a path of the root directory project.

#### Usage

Parameters can be only used in a theme info and in arguments of a tag. To use a parameter as a value you must surround the 
parameter with percent sings e.g. **%footheme.mobile_systems%**, just like in the symfony yaml services.

### Themes

```yml
# FooBundle/Resources/config/theme.yml
themes:
    bartheme:
        is_virtual: true
        # theme definition
    footheme:
        # theme definition
    # more themes
```

Each theme mapping file can contain many theme definitions. The `footheme` in the example is a unique name of the theme.
As you know we distinguish two types of theme: a virtual theme and a standard theme which are described just below.

#### Standard theme

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/fundamental-elements.md#theme)

The standard theme definition consists of the following keys:

```yml
footheme:
    # Required
    path: # an absolute path or a path to bundle resources
    # Optional
    tags:
        # tag list
    # Required
    info:
        # information definition
```

#### Virtual theme

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/fundamental-elements.md#virtualtheme)

A virtual theme definition is very similar to a standard theme definition. The loader recognizes virtual themes by
specifying the `is_virtual` key with the `true` value. 

```yml
footheme:
    is_virtual: true
    # Required
    themes: 
        # a list of referenced themes
    # Optional
    tags:
        # tag list
    # Required
    info:
        # information definition
```

To include themes to a virtual theme you will use a following formula:

```yml
footheme:
    is_virtual: true
    themes: 
        - { theme: footheme_mobile, as: mobile }
        # more subordinate themes
    # other keys
```

Or the shorthand version:

```yml
footheme:
    is_virtual: true
    themes: [ footheme_mobile ] # a collection of theme names
    # other keys
```

You must be cautious when referencing to themes, so please read these notes below:

* Each referenced theme is automatically moved to the corresponding virtual theme, so a referenced theme will be not 
accessible via the theme source,
* A theme that is going to be referenced may be referenced only once, so you cannot reference to the same theme twice,
* You cannot reference to another virtual theme,

### ThemeInfo

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/fundamental-elements.md#themeinfo)

```yml
info:
    key: value
    # other properties
```

Each key of the `info` maps appropriate field of the **ThemeInfo**, so keys that you can use are following:

Key | Type | Required
--- | ---- | --------
name | string | true
description | string | false
authors | collection | false

#### Defining an author

As you see the `authors` is of the collection type. To define an author you have to use the following formula:

```yml
info:
    authors:
        - { name: foo, email: foo@bar.com, homepage: www.bar.com }
        # other authors
```

Each author must also be of the collection type wherein you can only use the following keys:

Key | Type | Required
--- | ---- | --------
name | string | true
email | string | true
homepage | string | false

#### Parameters usage

Here is just a small snippet of how to use a defined parameter.

```yml
info:
    license: "%parameter_key%"
```

### Tags

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md)

```yml
tags:
    vendor.tag_name: # arguments
    # other tags
```

The `tags` element is a collection of tags which the theme will support. For use you have the following built-in tags:

#### Built-in tags

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#built-in-tags) to find 
out which of built-in tags you can use. 

Of course you can attach your own tags and use them as it was shown in the quick example. Generally tag names are taken 
from a tag registry that allows for dynamically registering tags in a much more convenient way. You can read about that 
[here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tag-registry).

#### Parameters usage

Sometimes arguments can be very long and thus, very hard to read, so in order to facilitate you can use parameters.

```yml
tags:
    foo.bar_tag: "%parameter_key%"
```

Final
-----

Now if you have properly created your theme mapping file you can finally load it.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)