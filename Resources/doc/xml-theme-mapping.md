XML Theme Mapping
=================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/XmlFileLoader.php)

Documents of this theme mapping are handled by the `Jungi\Bundle\ThemeBundle\Mapping\Loader\XmlFileLoader`. By default
the loader uses the `Jungi\Bundle\ThemeBundle\Core\Theme` for creating theme instances.

Prerequisites
-------------

Before you start I recommend to get familiar with the chapter [Theme Overview](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/themes-overview.md)
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
            <detail name="author.site">http://foo.com</detail>
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

Each of theme mapping file can contain one or multiple theme definitions. The `<theme />` element takes the role of theme
definition and each theme definition must have these attributes:

Attribute | Description | Required
--------- | ----------- | --------
name | An unique name of theme | true
path | An absolute path to a theme resources. The path to a bundle resource is also allowed | true

**NOTE**

> As show on the example above a value of the **path** attribute can be a bundle resource e.g. `@JungiFooBundle/Resources/theme`.
> This is possible thanks to using the `Symfony\Component\HttpKernel\Config\FileLocator` by the **XmlFileLoader**

Inside a theme definition can be only defined:

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
    <detail name="author.site">http://foo.com</detail>
    <detail name="description">description</detail>
    <detail name="license">MIT</detail>
</details>
```

The `<details />` element has only children `<detail />` which have one required attribute **name**. Values of this attribute
are just the same as keys of the default details implementation described in the **Theme Overview** chapter.

Name | Required
---- | --------
name | true
version | true
description | false
license | false
thumbnail | false
author.name | false
author.email | false
author.site | false

**INFO**

> The `<details />` element is required due to his two required children which are listed in the table


### Tags

[Click here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md) to know more about the
tags.

```xml
<tags>
    <tag name="jungi.environment">admin</tag>
    <tag name="jungi.desktop_devices" />
    <!-- the rest of tags -->
</tags>
```

The `<tags />` element has only children `<tag />` which have one required attribute **name**. This attribute takes as
value a unique tag name by whose we don't have to provide a full class name. You can use one of the following built-in tags.

Name | Class
---- | -----
jungi.mobile_devices | Jungi\Bundle\ThemeBundle\Tag\MobileDevices
jungi.desktop_devices | Jungi\Bundle\ThemeBundle\Tag\DesktopDevices
jungi.link | Jungi\Bundle\ThemeBundle\Tag\Link

Of course you can attach your own tags and use them like above. Generally tag names are taken from a tag registry which
allows for dynamically registering tags in much convenient way. You can read about a tag registry [here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/theme-tags.md#tag-registry).

#### Arguments

To facilitate creating tags you have the almost the same formula like in the symfony xml services. Inside each `<tag />`
element you can define the `<argument />` children which have only the **type** attribute.

The attribute **type** has the following values:

Type | Description
---- | -----------
string | A string value
constant | A constant value, a shortcut or a full qualified constant name
collection | A collection of argument

**NOTE**

> If you not define the **type** attribute for an argument, the XmlFileLoader will try evaluate the value of this argument
> to a php value, so e.g. true will be evaluated as the boolean type and not as string containing the "true". You
> can always cancel this behaviour by defining this attribute with the value **string**.

And here is the example of each argument:

```xml
<tag class="FooTag">
    <argument type="constant">FooTag::TYPE_BOO</argument>
    <argument type="collection">
        <argument type="string">foo</argument>
        <argument type="constant">Jungi\Bundle\ThemeBundle\Tag\BooTag::TYPE_ZOO</argument>
    </argument>
</tag>
```

As mentioned in the table the **constant** type of argument accepts a shortcut or a full qualified constant name. By
a shortcut I mean the notation `tag_name::constant` e.g. `jungi.mobile_devices::MOBILE`. This notation refers to a constant
located in a tag. Naturally you can refer to global constants e.g. **SOME_CONSTANT** and to constants located in classes
like in the example above.

Also you can define a scalar value for a `<tag />` element as shown on the example below:

```xml
<tags>
    <tag name="jungi.environment">admin</tag>
</tags>
```

This scalar value behaves like the following argument:

```xml
<tags>
    <tag name="jungi.environment">
        <argument>admin</argument>
    </tag>
</tags>
```

I assume that you're familiar with the **collection** and the **string** type from the symfony services :)

Final
-----

Now if you have properly created your theme mapping document you can finally load it. This is very easy and short task so
don't be impatient.

[Go to the final step](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/loading-theme-mapping.md)
[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)