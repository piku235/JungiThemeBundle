YAML Theme Mapping
==================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/YamlFileLoader.php)

Documents of this theme mapping are handled by the `Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlFileLoader`. By default
the loader uses the `Jungi\Bundle\ThemeBundle\Core\Theme` for creating theme instances.

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
    footheme.mobile.systems: [ iOS, AndroidOS ]
    footheme.mobile.device: "const@jungi.mobile_devices::MOBILE"

themes:
    footheme:
        path: "@JungiFooBundle/Resources/theme"
        tags:
            - name: jungi.desktop_devices
            - name: jungi.mobile_devices
              arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ]
            - name: jungi.environment
              arguments: admin
        details:
            name: An awesome theme
            author:
                name: piku235
                email: piku235@gmail.com
                site: http://test.pl
            version: 1.0.0
            license: MIT
            description: <i>foo desc</i>

```

**NOTE**

> You can name your yaml document as you like, there is no any special requirement to name it theme.yml

Getting Started
---------------

OK, so let's start explaining from `themes`, `parameters` will be mentioned together with tag arguments:

### Themes

```yml
# FooBundle/Resources/config/theme.yml
themes:
    footheme:
        # theme definition
```

Each document can define plenty of themes. The `footheme` in the example is the unique name of the theme. A theme
definition can only define elements like below:

```yml
footheme:
    # Required
    path: # an absolute path or a path to a bundle resources
    # Optional
    tags:
        # tag list
    # Required
    details:
        # details definition
```

**NOTE**

> As shown in the quick example a path can be a bundle resource `@JungiFooBundle/Resources/theme`. This is possible thanks
> to using the `Symfony\Component\HttpKernel\Config\FileLocator` by the **YamlFileLoader**

### Details

```yml
details:
    name: An awesome theme
    author:
        name: piku235
        email: piku235@gmail.com
        site: http://test.pl
    version: 1.0.0
    license: MIT
    description: <i>foo desc</i>
```

The `details` element can define children like in the table below. The children are almost the same as keys from the
default details implementation (described in the **Theme Overview** chapter), expect only the `author` element which has
own children.

Name | Child | Required
---- | ----- | --------
name | - | true
version | - | true
description | - | false
license | - | false
thumbnail | - | false
author | name | false
author | email | false
author | site | false

**INFO**

> The **details** element is required due to his two required children which are listed in the table

### Tags

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md) to know more about the
tags.

```yml
tags:
    - name: jungi.desktop_devices
    - name: jungi.mobile_devices
      arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ]
    - name: jungi.environment
      arguments: admin
```

The `tags` element is a list of tags which a theme will be supporting. Each entry of tag list represents a tag definition
where there are two variants of defining them. Which of them you will use depends only on you.

The first one:

```yml
tags:
    - name: jungi.desktop_devices
    - name: jungi.mobile_devices
      arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ]
```

or the second one:

```yml
tags:
    - { name: 'jungi.desktop_devices' }
    - { name: 'jungi.mobile_devices', arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ] }
```

#### Tag

For a tag definition you have available these elements:

Name | Description | Required
---- | ----------- | --------
name | A tag name | true
arguments | Arguments which are passed to a tag instance | false

The `name` element takes an unique tag name by whose we don't have to provide a full class name. You can use one of the
following built-in tags.

Name | Class
---- | -----
jungi.mobile_devices | Jungi\Bundle\ThemeBundle\Tag\MobileDevices
jungi.desktop_devices | Jungi\Bundle\ThemeBundle\Tag\DesktopDevices
jungi.link | Jungi\Bundle\ThemeBundle\Tag\Link

Of course you can attach your own tags and use them like above. Generally tag names are taken from a tag registry which
allows for dynamically registering tags in much convenient way. You can read about a tag registry [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tag-registry).

#### Arguments and parameters

```yml
parameters:
    footheme.mobile.systems: [ iOS, AndroidOS ]
    footheme.mobile.device: "const@jungi.mobile_devices::MOBILE"
    footheme.mobile.device_full: "const@Jungi\Bundle\ThemeBundle\FooClass::MOBILE"
```

Parameters facilitates providing arguments to a chosen tag. They have a local scope, so parameters defined in a document
will be only available in this document. To use a parameter as the tag argument you must surround the parameter with
percent sings "%" e.g. *%footheme.mobile.systems%*, just like in symfony yaml services. The **YamlFileLoader** automatically
will look for values of these parameters.

Usage example:

```yml
tags:
    - name: jungi.mobile_devices
      arguments: [ "%footheme.mobile.systems%", "const@jungi.mobile_devices::MOBILE" ]
```

##### Constants

Additionally I've provided support of constants. Like in the example above to call a constant you must only prepend it
with the `const@`. You can use a shortcut or a full qualified constant name as the constant value. By a shortcut I mean
the notation `tag_name::constant` e.g. `jungi.mobile_devices::MOBILE`. This notation refers to a constant located in a
tag. Naturally you can refer to global constants e.g. **SOME_CONSTANT** and to constants located in classes.

Final
-----

Now if you have properly created your theme mapping file you can finally load it.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)