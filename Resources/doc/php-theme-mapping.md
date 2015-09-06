PHP Theme Mapping
=================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/PhpDefinitionLoader.php)

Documents of this theme mapping are handled by the **PhpDefinitionLoader**. This like any other definition loader does 
not load themes right away.  

**IMPORTANT**

> There is one thing worthy to mention before you start. Everything in a theme mapping document has a local scope, so you 
> do not have to be afraid that something gets overridden. Themes at the beginning also have a local scope, only when they 
> are being added to a theme source they must have an unique name to prevent name conflicts.

Quick example
-------------

```php
<?php
// FooBundle/Resources/config/theme.php
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Core\Information\Author;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;

$definition = new ThemeDefinition();
$definition
    ->setPath('%kernel.root_dir%/Resources/theme')
    ->addTag(new Tag('jungi.desktop_devices'));
$registry->registerThemeDefinition('zoo_root', $definition);

$info = ThemeInfoEssence::createBuilder()
    ->setName('Virtual theme')
    ->setDescription('Super virtual theme')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com'))
    ->getThemeInfo();
$registry->registerThemeDefinition('virtual_child', new ThemeDefinition(
    '@JungiFooBundle/Resources/theme',
    array( new Tag('jungi.mobile_devices', array(array('iOS', 'AndroidOS'))) ),
    ThemeInfoImporter::import($info)
));

$definition = new VirtualThemeDefinition();
$definition->addThemeReference(new Reference('virtual_child', 'child'));
$definition->addTheme('direct_child', new ThemeDefinition('@JungiFooBundle/Resources/theme'));
$registry->registerThemeDefinition('virtual', $definition);
```

Getting started
---------------

Generally in a theme mapping file you have access to these below variables:

Variable | Description
-------- | -----------
$loader | holds the current PhpDefinitionLoader instance
$registry | holds the ParametricThemeDefinitionRegistry instance

### Parameters

Parameters have a local scope, so basically they have no use in php theme mappings, because you could simply replace them
by a php variable. Parameters are starting to have meaning when it comes to using global parameters, so we will focus 
here on how to use parameters. Using parameters is pretty straightforward, you only have to surround a parameter with 
percent sings e.g. **%footheme.mobile_systems%**, just like in the symfony services.

#### Global parameters

As you see the list below is very short at this moment, but as time goes on new global parameters can come.

Name | Description
---- | -----------
kernel.root_dir | parameter imported from the symfony service container, it returns a path of the root directory project.

### Themes

As you know we distinguish two types of theme: **standard** and **virtual**. 

#### Standard theme

[Show the definition class](https://github.com/piku235/JungiThemeBundle/blob/master/Mapping/ThemeDefinition.php)

A standard theme is represented by the **ThemeDefinition** class. 

```php
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfo;

// Setting via the constructor
$foo = new ThemeDefinition('Resources/theme', array(new Tag('jungi.desktop_devices')), new ThemeInfo());

// Setting via the setter methods
$bar = new ThemeDefinition();
$bar->setPath('Resources/theme');
$bar->addTag(new Tag('jungi.desktop_devices'));
$bar->setInfo(new ThemeInfo());
```

#### Virtual theme

[Show the definition class](https://github.com/piku235/JungiThemeBundle/blob/master/Mapping/VirtualThemeDefinition.php)

A virtual theme is represented by the **VirtualThemeDefinition** class and is almost the same as the **ThemeDefinition**,
expect you can not obviously set a path of a virtual theme.

```php
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;

$foo = new VirtualThemeDefinition(array(new Tag('jungi.desktop_devices')), new ThemeInfo());
// Adds a theme reference that can refer to a theme within the same mapping file
$foo->addThemeReference(new Reference('foo_child', 'child'));

// Setting via the setter methods
$bar = new VirtualThemeDefinition();
$bar->addTag(new Tag('jungi.desktop_devices'));
$bar->setInfo(new ThemeInfo());
// Adds a theme definition directly
$bar->addTheme('child', new ThemeDefinition());
```

#### Registering a theme

After you created your theme definition you have to register it to make it visible for the JungiThemeBundle, and now
it is the right time to use the `$registry` variable. 
 
```php
/* @var \Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition $definition */

$registry->registerThemeDefinition('footheme', $definition);
```

### ThemeInfo

[Show the definition class](https://github.com/piku235/JungiThemeBundle/blob/master/Mapping/ThemeInfo.php)

A theme info definition can be hard in using, because of its interface. The interface has been designed to be general
as much as possible. This approach give us a lot of benefits, if you wanted e.g. create your own theme info that 
could have extra fields you would not have to make changes in theme mapping loaders at all. An only disadvantage of this 
approach will start to be visible when comes to using a theme info definition directly. 

```php
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfo;

$definition = new ThemeInfo();
$definition->setProperty('name', 'SimpleTheme');
$definition->setProperty('description', 'a beautiful theme');
$definition->setProperty('authors', array(
    array('name' => 'Piotr Kugla', 'email' => 'piku235@gmail.com', 'homepage' => 'http://foo.com'),
    array('name' => 'Foo Bar', 'email' => 'foo@bar.com')
));
```

As you can see in the example above the code readability is quite poor and you must be also cautious when defining 
bigger properties like e.g. **authors**. To ease creating theme info definitions there were provided the **ThemeInfoImporter** 
which is mentioned just below.

#### ThemeInfoImporter

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Mapping/ThemeInfoImporter.php)

The sole purpose of this class is ability of importing objects of the `Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfo`. 
In a connection with the **ThemeInfoImporter** you can use e.g. `Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence` 
class to build a theme info definition.

```php
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Core\Information\Author;

$info = ThemeInfoEssence::createBuilder()
    ->setName('SimpleTheme')
    ->setDescription('a simple theme with the beautiful design')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'foo.com'))
    ->getThemeInfo();

$definition = ThemeInfoImporter::import($info);
```

Now is much better, we have got much more readable code and easier in use. The **ThemeInfoImporter** is doing everything 
for us, it simply converts a **ThemeInfoEssence** instance to a **ThemeInfo** definition instance.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)