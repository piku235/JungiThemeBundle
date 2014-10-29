YAML Theme Mapping
==================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/YamlFileLoader.php)

Documents of this theme mapping are handled by the **YamlFileLoader**. By default the loader uses the `Jungi\Bundle\ThemeBundle\Core\Theme` 
for creating theme instances.

Prerequisites
-------------

Before you start I recommend to get familiar with the chapter [Theme Overview](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md)
to understand the further things located here.

Quick example
-------------

Here is the simple document which defines the single theme with basic elements:

```yml
# FooBundle/Resources/config/theme.yml
parameters:
    footheme.mobile_systems: [ iOS, AndroidOS ]
    footheme.mobile_device: "const@jungi.mobile_devices::MOBILE"

themes:
    footheme:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            jungi.desktop_devices: ~
            jungi.mobile_devices: [ "%footheme.mobile_systems%", "%footheme.mobile_device%" ]
            jungi.environment: admin
        info:
            name: An awesome theme
            version: 1.0.0
            license: MIT
            description: <i>foo desc</i>
            authors:
                - { name: piku235, email: piku235@gmail.com, homepage: www.foo.com }

```

**NOTE**

> You can name your yaml document as you like, there is no any special requirement to name it theme.yml

Getting Started
---------------

OK, so let's start explaining from the `parameters`, the `themes` will be discussed after that:

### Parameters

```yml
parameters:
    key: value
    # other parameters
```

Parameters can facilitate many things, especially when you've got the definition of multiple themes. They're almost the 
same as parameters in the symfony services, with the difference that parameters in a theme mapping file has a local scope, 
so you don't must be afraid that some variable will be overwritten by other theme mapping file.

#### Constants

```yml
parameters:
    footheme.mobile_device: "const@jungi.mobile_devices::MOBILE"
    footheme.mobile_device.global: "const@CONSTANT_BOO"
    footheme.mobile_device.full: "const@Jungi\Bundle\ThemeBundle\FooClass::MOBILE"
```

Additionally the support of constants was introduced. Like in the example above to call a constant you must only prepend 
it with the `const@`. You can use a shortcut or a full qualified constant name. By the shortcut I mean the notation 
`tag_name::constant` e.g. `jungi.mobile_devices::MOBILE` where it refers to a constant located in a tag. Naturally you can 
refer to global constants e.g. **SOME_CONSTANT** and to constants located in classes like in the example above.

#### Usage

Parameters can be only used in the info and in arguments of tag. To use a parameter as a value you must surround the 
parameter with percent sings "%" e.g. **%footheme.mobile_systems%**, just like in the symfony yaml services.

### Themes

```yml
# FooBundle/Resources/config/theme.yml
themes:
    footheme:
        # theme definition
    # other themes
```

Each theme mapping file can contain many theme definitions. The `footheme` in the example is the unique name of the theme.
A theme definition can only define keys like below:

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

**NOTE**

> As shown in the quick example a path can be a bundle resource `@JungiFooBundle/Resources/theme`.

### ThemeInfo

```yml
info:
    key: value
    # other properties
```

From the **Themes overview** chapter you should know what is the **ThemeInfo**. Each key of the `info` maps appropriate 
field of the **ThemeInfo**, so keys that you can use are following:

Key | Type | Required
--- | ---- | --------
name | string | true
version | string | false
description | string | false
license | string | false
authors | collection | false

As you see the `authors` is a collection type. to define an author you have the following formula:

```yml
info:
    authors:
        - { name: foo, email: foo@bar.com, homepage: www.bar.com }
        # other authors
```

Each author must be also a collection type and an author can have only the following keys:

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

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md) to know more about the
tags.

```yml
tags:
    vendor.tag_name: # arguments
    # other tags
```

The `tags` element is a collection of tags which the theme will support.

Name | Class
---- | -----
jungi.mobile_devices | MobileDevices
jungi.desktop_devices | DesktopDevices
jungi.link | Link

Of course you can attach your own tags and use them like above. Generally tag names are taken from a tag registry which
allows for dynamically registering tags in much convenient way. You can read about a tag registry [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tag-registry).

#### Arguments

Arguments are significant when you want pass some data to a tag. Here's an example of passing arguments to the `jungi.mobile_devices`
tag.

```yml
tags:
    jungi.mobile_devices: [ [ AndroidOS, WindowsPhoneOS ], "const@jungi.mobile_devices::MOBILE" ]
```

#### Parameters usage

Sometimes arguments can be very long and thus very hard to read, so to simplify you can use parameters.

```yml
tags:
    foo.bar_tag: "%parameter_key%"
```

Final
-----

Now if you have properly created your theme mapping file you can finally load it.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)