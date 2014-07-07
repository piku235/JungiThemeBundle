XML Mapping
===========

The theme mapping allows you to easily create new themes. XML mapping documents are handled by the XmlFileLoader which is
located [here](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/XmlFileLoader.php). This loader uses
the `Jungi\Bundle\ThemeBundle\Core\Theme` class for creating theme instances.

Prerequisites
-------------

Before you start I recommend to get familiar with the chapter [**Theme Overview**](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/theme-overview.md)
to understand the further things located here.

Structure
---------

The definition of a document looks like following:

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

Here is a simple document which defines the single theme with basic elements:

```xml
<!-- FooBundle/Resources/config/theme.xml -->
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <theme name="foo" path="@JungiFooBundle/Resources/theme">
        <tags>
            <tag name="jungi.environment">admin</tag>
            <tag name="jungi.desktop_devices" />
            <tag name="jungi.mobile_devices">
                <argument type="collection">
                    <argument>iOS</argument>
                    <argument>AndroidOS</argument>
                    <argument>WindowsPhoneOS</argument>
                </argument>
                <argument type="constant">jungi.mobile_devices::MOBILE</argument>
            </tag>
        </tags>
        <details>
            <detail name="name">Foo theme</detail>
            <detail name="version">1.0.0</detail>
            <detail name="author.name">Piotr Kugla</detail>
            <detail name="author.email">piku235@gmail.com</detail>
            <detail name="author.www">http://foo.com</detail>
            <detail name="description">description</detail>
            <detail name="license">MIT</detail>
        </details>
    </theme>

</theme-mapping>
```

**NOTE**

> You can name your xml document as you like, there is no any special requirement to name it theme.xml

Getting Started
---------------

So let's start explaining from the theme definition:

### Theme

```xml
<theme-mapping>
    <theme name="foo" path="@JungiFooBundle/Resources/theme">
        <!-- definition -->
    </theme>
</theme-mapping>
```

Each of XML Mapping File can contain one or multiple theme definitions. The `<theme />` element takes the role of theme
definition and each theme definition must have these attributes:

Attribute | Description | Required
--------- | ----------- | --------
name | An unique name of a theme | true
path | An absolute path to a theme resources. The path to a bundle resource is also allowed | true

**NOTICE**

> As show on the example above the path can be a bundle resource `@JungiFooBundle/Resources/theme`. This is possible thanks
> to using the `Symfony\Component\HttpKernel\Config\FileLocator` by the **XmlFileLoader**

Inside the theme definition can be only defined:

```xml
<!-- tags are optional -->
<tags>
    <!-- tag list -->
</tags>
<!-- details are required -->
<details>
    <!-- detail list -->
</details>
```

### Details

```xml
<details>
    <detail name="name">Foo theme</detail>
    <detail name="version">1.0.0</detail>
    <detail name="author.name">Piotr Kugla</detail>
    <detail name="author.email">piku235@gmail.com</detail>
    <detail name="author.www">http://foo.com</detail>
    <detail name="description">description</detail>
    <detail name="license">MIT</detail>
</details>
```

The `<details />` element has only children `<detail />` which have only one required attribute **name**.

**INFO**

> The `<details />` element is required due to his two required children which are listed in the table

The attribute **name** takes the following values:

Name | Required
---- | --------
name | true
version | true
description | false
license | false
author.name | false
author.email | false
author.www | false

### Tags

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md) to know more about the tags

```xml
<tags>
    <tag name="jungi.environment">admin</tag>
    <tag name="jungi.desktop_devices" />
    <!-- the rest of tags -->
</tags>
```

The `<tags />` element has only children `<tag />` which have following attributes:

Attribute | Description | Required
--------- | ----------- | --------
type | A tag name | true

Here is a list of included tags in the bundle:

Class | Name
----- | ----
Jungi\Bundle\ThemeBundle\Tag\MobileDevices | jungi.mobile_devices
Jungi\Bundle\ThemeBundle\Tag\DesktopDevices | jungi.desktop_devices
Jungi\Bundle\ThemeBundle\Tag\Link | jungi.link

**INFO**

> The tag names are taken from the TagRegistry instance. TagRegistry allows for dynamically registering new tags in much
> convenient way

Now is the right time to talk about arguments for a tag.

#### Arguments

To facilitate creating tags you have the almost the same formula like in the symfony xml services. Inside each `<tag />`
element you can define `<argument />` children. These children has only the attribute **type** which have some special
meaning for the XmlFileLoader.

The attribute **type** has the following values:

Type | Description
---- | -----------
string | A string value
constant | A constant value, a shortcut or a full qualified constant name
collection | A collection of argument

**NOTICE**

> If you not define the **type** attribute for an argument, the XmlFileLoader will try evaluate the value of this argument
> to the php value, so for e.g. true will be evaluated to the boolean type and not as string containing the "true". You
> can always cancel this behaviour by defining the attribute **type** with the value **string**.

And here is an example of each argument:

```xml
<tag class="FooTag">
    <argument type="constant">FooTag::TYPE_BOO</argument>
    <argument type="collection">
        <argument type="string">foo</argument>
        <argument type="constant">Jungi\Bundle\ThemeBundle\Tag\BooTag::TYPE_ZOO</argument>
    </argument>
</tag>
```

As mentioned in the table the **constant** type of an argument accepts a shortcut or a full qualified constant name. As
the shortcut I mean the notation "tag_type::constant" for eg. "jungi.mobile_devices::MOBILE". This notation refers to a
constant located in the tag. Naturally you can refer to a global constants for eg. "SOME_CONSTANT" and to constants located
in classes like in the example above.

Also you can define a scalar value for a `<tag />` element as shown on the example below:

```xml
<tags>
    <tag name="jungi.environment">admin</tag>
</tags>
```

This scalar value behaves like a following argument:

```xml
<tags>
    <tag name="jungi.environment">
        <argument>admin</argument>
    </tag>
</tags>
```

I assume that you're familiar with the **collection** and the **string** type from the symfony services (:

Final
-----

Now if you have properly created your theme mapping document you can finally load it. This is very easy and short task so
don't panic.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mappings.md)