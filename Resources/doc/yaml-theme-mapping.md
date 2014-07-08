YAML Mapping
============

The theme mapping allows you to easily create new themes. YAML mapping documents are handled by the YamlFileLoader which
is located [here](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/YamlFileLoader.php). This loader
uses the `Jungi\Bundle\ThemeBundle\Core\Theme` class for creating theme instances.

Prerequisites
-------------

Before you start I recommend to get familiar with the chapter [**Theme Overview**](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-overview.md)
to understand the further things located here.

Quick example
-------------

Here is a simple document which defines the single theme with the basic elements:

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
                www: http://test.pl
            version: 1.0.0
            license: MIT
            description: <i>foo desc</i>

```

**NOTE**

> You can name your yaml document as you like, there is no any special requirement to name it theme.yml

Getting Started
---------------

OK, so let's start explaining from themes, parameters will be mentioned together with tag arguments:

### Themes

```yml
themes:
    footheme:
        # theme definition
```

Each of YAML document can have plenty of themes. The `footheme` in the example is a unique name of the theme. Each theme
definition defines elements like below:

```yml
footheme:
    # Required
    path: # an absolute path or the path to a bundle resources
    # Optional
    tags:
        # tag list
    # Required
    details:
        # details definition
```

**NOTICE**

> As shown on the example the path can be a bundle resource `@JungiFooBundle/Resources/theme`. This is possible thanks to
> using the `Symfony\Component\HttpKernel\Config\FileLocator` by the **YamlFileLoader**

### Details

```yml
details:
    name: An awesome theme
    author:
        name: piku235
        email: piku235@gmail.com
        www: http://test.pl
    version: 1.0.0
    license: MIT
    description: <i>foo desc</i>
```

The **details** element can define children like in the table below and as shown in the example above:

Name | Child | Required
---- | ----- | --------
name | - | true
version | - | true
description | - | false
license | - | false
author | name | false
author | email | false
author | www | false

Only the **author** element can has own children.

**INFO**

> The **details** element is required due to his two required children which are listed in the table

### Tags

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md) to know more about the tags

```yml
tags:
    - name: jungi.desktop_devices
    - name: jungi.mobile_devices
      arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ]
    - name: jungi.environment
      arguments: admin
```

There are two variants of defining tag list:

```yml
tags:
    - name: jungi.desktop_devices
    - name: jungi.mobile_devices
      arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ]
```

or:

```yml
tags:
    - { name: 'jungi.desktop_devices' }
    - { name: 'jungi.mobile_devices', arguments: [ "%footheme.mobile.systems%", "%footheme.mobile.device%" ] }
```

It depends on you which of these variants you will be using.

For each tag you have these elements:

Name | Description | Required
---- | ----------- | --------
type | A tag name | true
arguments | Arguments which are passed to a tag object | false

Here is a list of included tags in the bundle:

Class | Name
----- | ----
Jungi\Bundle\ThemeBundle\Tag\MobileDevices | jungi.mobile_devices
Jungi\Bundle\ThemeBundle\Tag\DesktopDevices | jungi.desktop_devices
Jungi\Bundle\ThemeBundle\Tag\Link | jungi.link

**INFO**

> The tag names are taken from the TagRegistry instance. TagRegistry allows for dynamically registering new tags in much
> convenient way

For better operating on arguments you have for use the parameters.

#### Parameters

```yml
parameters:
    footheme.mobile.systems: [ iOS, AndroidOS ]
    footheme.mobile.device: "const@jungi.mobile_devices::MOBILE"
    footheme.mobile.device_full: "const@Jungi\Bundle\ThemeBundle\FooClass::MOBILE"
```

Parameters facilitates providing arguments to a chosen tag. They have a local scope, so parameters defined in the document
will be only available in this document. To use a parameter as the tag argument you must surround the parameter with
percent sings "%" e.g. *%footheme.mobile.systems%*, just like in symfony yaml services. YamlFileLoader automatically
will look for values of these parameters.

##### Constants

Additionally I've provided support of constants. Like in the example above to call a constant you must only prepend it
with the "const@". You can use a shortcut or a full qualified constant name as the constant value. As the shortcut I mean
the notation "tag_type::constant" e.g. "jungi.mobile_devices::MOBILE". This notation refers to a constant located in
the tag. Naturally you can refer to a global constants e.g. "SOME_CONSTANT" and to constants located in classes like
in the example above.

Final
-----

Now if you have properly created your theme mapping file you can finally load it. This is very easy and short task so
don't panic.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mappings.md)