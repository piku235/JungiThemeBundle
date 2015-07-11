Loading theme mapping files
===========================

After you have completed creating your theme mapping file you can finally load it to a theme source. To achieve this goal
you have for use the following theme mapping loaders.

Format | Class (default)
------ | ---------------
xml | Jungi\Bundle\ThemeBundle\Mapping\Loader\XmlDefinitionLoader
yaml | Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlDefinitionLoader
php | Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpDefinitionLoader

Loading from a bundle
---------------------

First open the file where is located your bundle class e.g. `src/Foo/FooBundle/BooFooBundle.php`. After that define
the **build** method in the bundle class if you don't have it actually. Thanks to that method we are able to load themes 
in a comfortable way.

### Overview

For loading mapping files was introduced the `registerMappingFile` method of the bundle extension. Method is very simple 
and its task is to pass registered mapping files to the theme initializer who will load all themes at one place.

```php
<?php
// src/Foo/FooBundle/BooFooBundle.php
namespace Boo\BooFooBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BooFooBundle
 */
class BooFooBundle extends Bundle
{
	/**
	 * {@inheritdoc}
	 */
	public function boot()
	{
	    /* @var \Jungi\Bundle\ThemeBundle\DependencyInjection\JungiThemeExtension $ext */
	    $ext = $builder->getExtension('jungi_theme');
        $ext->registerMappingFile(__DIR__.'/Resources/config/theme.xml', 'xml'); // with the specified loader
        $ext->registerMappingFile(__DIR__.'/Resources/config/theme.yml'); // default
	}
}
```

Summary
-------

And that's all :)

If your bundle is enabled in the **AppKernel** the theme(s) from the mapping file should be now loaded. You can check
it by accessing a theme source.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)