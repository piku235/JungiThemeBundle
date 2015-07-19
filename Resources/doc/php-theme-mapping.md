PHP Theme Mapping
=================

[Show the loader](https://github.com/piku235/JungiThemeBundle/tree/master/Mapping/Loader/PhpDefinitionLoader.php)

Documents of this theme mapping are handled by the **PhpDefinitionLoader**. This like any other definition loader does 
not load themes right away.  

**IMPORTANT**

There is one thing worthy to mention before you start. Everything in a theme mapping document has a local scope, so you 
do not have to be afraid that something gets overridden. Themes at the beginning also have a local scope, only when they 
are being added to a theme source they must have an unique name to prevent name conflicts.

Quick example
-------------

```php
<?php
// FooBundle/Resources/config/theme.php
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;

$definition = new StandardThemeDefinition();
$definition
    ->setPath('@JungiMainThemeBundle/Resources/theme')
    ->addTag(new Tag('jungi.desktop_devices'));
$registry->registerThemeDefinition('zoo_first', $definition);

$info = ThemeInfoEssence::createBuilder()
    ->setName('Virtual theme')
    ->setDescription('Super virtual theme')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com'))
    ->getThemeInfo();
    
$registry->registerThemeDefinition('zoo_second', new StandardThemeDefinition(
  '@JungiMainThemeBundle/Resources/theme',
  array( new Tag('jungi.mobile_devices', array(array('iOS', 'AndroidOS'))) ),
  ThemeInfoImporter::import($info)
));
```

Getting started
---------------

As you have seen in the quick example there are two ways of creating theme definition. The first is by using the setter 
methods and the second is by using the constructor arguments.

at work ...

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)