XML Theme Mapping
=================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/XmlFileLoader.php)

Documents of this theme mapping are handled by the **XmlFileLoader**. By default the loader uses the `Jungi\Bundle\ThemeBundle\Core\Theme` 
for creating theme instances.

Prerequisites
-------------

Before you start I recommend to get familiar with the chapter [Themes Overview](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md)
to understand the further things located here.

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

Here is the simple document which defines the single theme with basic elements:

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
        <theme name="foo" path="@JungiFooBundle/Resources/theme">
            <tags>
                <tag name="jungi.environment">admin</tag>
                <tag name="jungi.desktop_devices" />
                <tag name="jungi.mobile_devices">
                    <argument type="collection">%mobile_devices%</argument>
                    <argument type="constant">jungi.mobile_devices::MOBILE</argument>
                </tag>
            </tags>
            <info>
                <property key="authors">%authors%</property>
                <property key="name">Foo theme</property>
                <property key="version">1.0.0</property>
                <property key="description">description</property>
                <property key="license">MIT</property>
            </info>
        </theme>
    </themes>
    
</theme-mapping>
```

**NOTE**

> You can name your xml document as you like, there is no any special requirement to name it theme.xml

Getting Started
---------------

So let's start explaining from the `<parameters />` element, the `<themes />` element will be discussed after that.

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

Parameters can facilitate many things, especially when you have the definition of multiple themes. They're almost the 
same as parameters in the symfony services, expect that parameters in a theme mapping file has a local scope, so you 
don't must be afraid that some variable will be overwritten by other theme mapping file.

The parameter is specified by the `<parameter />` element which is a direct child of the `<parameters />` element. 
The `<parameter />` element has the following attributes:

Attribute | Description | Required
--------- | ----------- | --------
key | A unique key under which the parameter will be accessed | false
type | A type of the element | false

**NOTE**

> If you'll not define the **type** attribute for a parameter, the XmlFileLoader will try evaluate the value of this parameter
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
<parameter type="constant">jungi.mobile_devices::MOBILE</parameter>
<parameter type="constant">CONSTANT_BOO</parameter>
<parameter type="constant">Jungi\Bundle\ThemeBundle\Tag\BooTag::TYPE_ZOO</parameter>
```

As mentioned in the types table the **constant** type of argument accepts a shortcut or a full qualified constant name. 
By the shortcut I mean the notation `tag_name::constant` e.g. `jungi.mobile_devices::MOBILE` where it refers to a constant 
located in a tag. Naturally you can refer to global constants e.g. **SOME_CONSTANT** and to constants located in classes
like in the example above.

#### Usage

Parameters can be only used in properties of the info and in arguments of tag. To use a parameter as a value you must 
surround the parameter with percent sings "%" e.g. **%footheme.mobile_systems%**, just like in the symfony xml services.

### Themes

```xml
<theme-mapping>
    <themes>
        <theme name="" path="">
            <!-- definition -->
        </theme>
        <!-- other themes -->
    </themes>
</theme-mapping>
```

Each of theme mapping file can contain the definition of one or multiple themes. The `<themes />` element is a place where 
you defines your themes by specifying directly the `<theme />` element. The `<theme />` element has the following attributes:

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

### ThemeInfo

```xml
<info>
    <property key="one_of_keys">
        <!-- value depending on the property type -->
    </property>
    <!-- other properties -->
</info>
```

From the **Themes overview** chapter you should know what is the **ThemeInfo**. The `<property />` element specifies 
the properties of the **ThemeInfo**. It has the same structure as the `<parameter />` element, so you have the same attributes 
as there.

**NOTE**

> The **key** attribute of the `<property />` element is required if is directly defined in the `<info />` element.

The available keys:

Key | Type | Required
--- | ---- | --------
name | string | true
version | string | false
description | string | false
license | string | false
authors | collection | false

As you've seen in the quick example to define an author you'll use the following formula:

```xml
<info>
    <property key="authors" type="collection">
        <property type="collection">
            <parameter key="name">foo</parameter>
            <parameter key="email">foo@bar.com</parameter>
            <parameter key="homepage">www.bar.com</parameter>
        </property>
        <!-- other authors -->
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

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md) to know more about the
tags.

```xml
<tags>
    <tag name="vendor.tag_name">
        <!-- arguments -->
    </tag>
    <!-- other tags -->
</tags>
```

The `<tags />` element has only children `<tag />` which have one required attribute **name**. This attribute takes as
the value a unique tag name which identifies a tag. 

For use you have the following built-in tags:

Class | Name
----- | ----
MobileDevices | jungi.mobile_devices
DesktopDevices | jungi.desktop_devices
VirtualTheme | jungi.virtual_theme

Of course you can attach your own tags and use them like above. Generally tag names are taken from a tag registry which
allows for dynamically registering tags in a much more convenient way. You can read about that [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tag-registry).

**IMPORTANT**

There is also one thing worthy to say here. If you're creating a standard theme which generally will be used for desktop 
devices you must remember to define the `jungi.desktop_device` tag. In some cases this tag can be very useful when viewing 
information about a theme.

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