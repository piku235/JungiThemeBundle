XML Theme Mapping
=================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/XmlDefinitionLoader.php)

XML documents are handled by the **XmlDefinitionLoader**. This like any other definition loader does not load themes 
right away.  

**IMPORTANT**

> There is one thing worthy to mention before you start. Everything in a theme mapping document has a local scope, so you 
> do not have to be afraid that something gets overridden. Themes at the beginning also have a local scope, only when they 
> are being added to a theme source they must have an unique name to prevent name conflicts.

Structure
---------

The definition of document looks like following:

```xml
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <!-- your themes definitions goes here -->
</theme-mapping>
```

Quick example
-------------

```xml
<!-- FooBundle/Resources/config/theme.xml -->
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <parameters>
        <parameter key="mobile_devices" type="collection">
            <parameter>iOS</parameter>
            <parameter>AndroidOS</parameter>
            <parameter>WindowsPhoneOS</parameter>
        </parameter>
        <parameter key="authors" type="collection">
            <parameter type="collection">
                <parameter key="name">Piotr Kugla</parameter>
                <parameter key="email">piku235@gmail.com</parameter>
                <parameter key="homepage">www.foo.com</parameter>
            </parameter>
        </parameter>
    </parameters>

    <themes>
        <virtual-theme name="foo_adaptive">
            <themes>
                <ref theme="foo_adaptive_mobile" as="mobile" />
                <ref theme="boo" />
            </themes>
            <tags>
                <tag name="jungi.desktop_devices" />
                <tag name="jungi.mobile_devices">%mobile_devices%</tag>
                <tag name="jungi.tablet_devices">%mobile_devices%</tag>
            </tags>
            <info>
                <property key="name">FooAdaptive</property>
                <property key="authors" type="collection">
                    <property type="collection">
                        <property key="name">piku235</property>
                        <property key="email">piku235@gmail.com</property>
                    </property>
                </property>
            </info>
        </virtual-theme>
        <theme name="foo_adaptive_mobile" path="@JungiMainThemeBundle/Resources/theme">
            <tags>
                <tag name="jungi.mobile_devices">%mobile_devices%</tag>
                <tag name="jungi.tablet_devices">%mobile_devices%</tag>
            </tags>
        </theme>
        <theme name="boo" path="@JungiMainThemeBundle/Resources/theme">
            <tags>
                <tag name="jungi.desktop_devices" />
            </tags>
        </theme>
        <theme name="foo" path="@JungiFooBundle/Resources/theme">
            <tags>
                <tag name="jungi.environment">admin</tag>
                <tag name="jungi.desktop_devices" />
                <tag name="jungi.mobile_devices">%mobile_devices%</tag>
                <tag name="jungi.tablet_devices">%mobile_devices%</tag>
            </tags>
            <info>
                <property key="authors">%authors%</property>
                <property key="name">Foo Theme</property>
                <property key="description">description</property>
            </info>
        </theme>
    </themes>
    
</theme-mapping>
```

**NOTE**

> You can name your xml document as you like, there is no any special requirement to name it theme.xml

Getting Started
---------------

So let's start explaining from the top - the `<parameters />` element.

### Parameters

```xml
<theme-mapping>
    <parameters>
        <parameter key="simple_parameter">
            <!-- value depending on the parameter type -->
        </parameter>
        <!-- other parameters -->
    </parameters>
</theme-mapping>
```

Parameters can facilitate many things, especially when you have multiple theme definitions. They are exactly the same as 
parameters in the symfony services, expect that parameters in a theme mapping file has a local scope.

The parameter as you can guess is specified by the `<parameter />` element which is a direct child of the `<parameters />` 
element. The `<parameter />` element defines the following attributes:

Attribute | Description | Required
--------- | ----------- | --------
key | A unique key under which the parameter will be accessed | false
type | A type of the element | false

**NOTE**

> If you will not define the **type** attribute for a parameter, the loader will try evaluate the value of this parameter
> to a php value, so e.g. true will be evaluated as the boolean type and not as string containing the "true". You can 
> always cancel this behaviour by defining this attribute with the **string** value.

And for the **type** attribute you have these available values:

Type | Description
---- | -----------
string | A string type
constant | A constant value, a shortcut or a full qualified constant name
collection | An array representation

#### Constant

```xml
<parameter type="constant">jungi.fake::SPECIAL</parameter>
<parameter type="constant">CONSTANT_BOO</parameter>
<parameter type="constant">Jungi\Bundle\ThemeBundle\Tag\BooTag::TYPE_ZOO</parameter>
```

As mentioned in the types table the **constant** type of argument accepts a shortcut or a full qualified constant name. 
The shortcut has the notation `tag_name::constant` e.g. `jungi.fake::SPECIAL` where it refers to a constant 
located in a tag. Naturally you can refer to global constants as well e.g. **SOME_CONSTANT** and to constants located in 
classes like in the example above.

#### Global parameters

To facilitate some things were introduced following global parameters:

Name | Description
---- | -----------
kernel.root_dir | parameter imported from the symfony service container, it returns a path of the root directory project.

#### Usage

Parameters can be only used in properties of theme info and in arguments of a tag. To use a parameter as a value you must 
surround the parameter with percent sings e.g. **%footheme.mobile_systems%**, just like in the symfony xml services.

### Themes

```xml
<theme-mapping>
    <themes>
        <virtual-theme name="">
            <!-- definition -->
        </virtual-theme>
        <theme name="" path="">
            <!-- definition -->
        </theme>
        <!-- more themes -->
    </themes>
</theme-mapping>
```

Each of theme mapping file can contain a definition of one or multiple themes. The `<themes />` element is a place where 
you can define your themes. As you know we distinguish two types of theme: a virtual theme that is specified by the 
`<vritual-theme />` element and a standard theme that is specified by the `<theme />` element.

#### Standard theme

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/fundamental-elements.md#theme)

The `<theme />` element represents the standard theme and has the following attributes:

Attribute | Description | Required
--------- | ----------- | --------
name | An unique name of theme | true
path | An absolute path to theme resources. The path to a bundle resource is also allowed | true

**NOTE**

> As shown in the quick example a path can be a bundle resource e.g. `@JungiFooBundle/Resources/theme`.

Inside the `<theme />` element can be only defined:

```xml
<theme name="" path="">
    <!-- tags are optional -->
    <tags>
        <!-- tag list -->
    </tags>
    <!-- info is required -->
    <info>
        <!-- list of properties -->
    </info>
</theme>
```

#### Virtual theme

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/fundamental-elements.md#virtualtheme)

In the contrary to the `<theme />` element, the `<virtual-theme />` element has only one following attribute:

Attribute | Description | Required
--------- | ----------- | --------
name | An unique name of virtual theme | true

The `<virtual-theme />` element can also have tags and information. 

```xml
<virtual-theme name="">
    <!-- themes are required -->
    <themes>
        <!-- list of referenced themes -->
    </themes>
    <!-- tags are optional -->
    <tags>
        <!-- tag list -->
    </tags>
    <!-- info is required -->
    <info>
        <!-- list of properties -->
    </info>
</virtual-theme>
```

As you have noticed the `<virtual-theme />` element defines `<themes />` element where inside this element you can place 
subordinate themes for a virtual theme.

```xml
<virtual-theme name="">
    <themes>
        <ref theme="foo_mobile" as="mobile" />
        <!-- more references -->
    </themes>
    <!-- other -->
</virtual-theme>
```

You must be cautious when referencing to themes, so please read these notes below:

* Each referenced theme is automatically moved to the corresponding virtual theme, so a referenced theme will be not 
accessible via the theme source,
* A theme that is going to be referenced may be referenced only once, so you cannot reference to the same theme twice,
* You cannot reference to another virtual theme,

### ThemeInfo

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/fundamental-elements.md#themeinfo)

```xml
<info>
    <property key="one_of_keys">
        <!-- value depending on the property type -->
    </property>
    <!-- more properties -->
</info>
```

The `<property />` element specifies the properties of the **ThemeInfo**. It has the same structure as the `<parameter />` 
element, so you have the same attributes as there.

The available keys:

Key | Type | Required
--- | ---- | --------
name | string | true
description | string | false
authors | collection | false

#### Defining an author

As you have seen in the quick example to define an author you will use the following formula:

```xml
<info>
    <property key="authors" type="collection">
        <property type="collection">
            <parameter key="name">foo</parameter>
            <parameter key="email">foo@bar.com</parameter>
            <parameter key="homepage">www.bar.com</parameter>
        </property>
        <!-- more authors -->
    </property>
</info>
```

An author must be defined as a property of the collection type, where the children of the `<property />` element can only 
have the following keys:

Key | Type | Required
--- | ---- | --------
name | string | true
email | string | true
homepage | string | false

#### Parameters usage

Here is just a small snippet of how to use a defined parameter in every `<property />` element.

```xml
<info>
    <property key="license">%parameter_key%</property>
</info>
```

### Tags

[Get info](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md)

```xml
<tags>
    <tag name="vendor.tag_name">
        <!-- arguments -->
    </tag>
    <!-- other tags -->
</tags>
```

The `<tags />` element may only consist of `<tag />` elements. The `<tag />` element has only one attribute:

Attribute | Description | Required
--------- | ----------- | --------
name | The attribute takes as the value a unique tag name which identifies a tag. | true

#### Built-in tags

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#built-in-tags) to find 
out which of built-in tags you can use. 

Of course you can attach your own tags and use them as it was shown in the quick example. Generally tag names are taken 
from a tag registry that allows for dynamically registering tags in a much more convenient way. You can read about that 
[here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tag-registry).

#### Arguments

```xml
<tags>
    <tag name="vendor.tag_name">
        <argument>
            <!-- value depending on the argument type -->
        </argument>
        <!-- other arguments -->
    </tag>
</tags>  
```

To pass data to a tag you'll use the `<argument />` element which has the same structure as the `<parameter />` element,
so you must follow the same things as for the `<parameter />` element to create an `<argument />` element.

#### Parameters usage

Here is just a small snippet of how to use a defined parameter in the `<argument />` element.

```xml
<tags>
    <tag name="vendor.tag_name">
        <argument>%parameter_key%</argument>
    </tag>
</tags>  
```

Final
-----

Now if you have properly created your theme mapping document you can finally load it.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)