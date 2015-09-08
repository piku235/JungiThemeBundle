Loading theme mapping files
===========================

By first you must remember that the JungiThemeBundle does not load theme mapping files automatically, so you have to do 
it manually. As you know themes resides in a bundle and to make them available in an app you have a possibility of 
registering theme mapping files by the JungiThemeBundle extension or by application configuration.

Load via bundle extension
-------------------------

First open a file where is located your bundle class e.g. `src/Foo/FooBundle/BooFooBundle.php`. After that define
the **build** method in the bundle class if you do not have it actually. Thanks to that method we are able to load themes 
in much comfortable way.

Now you have to use the `registerMappingFile` method of the bundle extension to register your theme mapping file for load. 
Method is very simple and its task is to pass registered mapping files to a theme source initializer that will load all 
themes at one place.

```php
<?php
// src/Jungi/FooBundle/JungiFooBundle.php
namespace Jungi/FooBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * JungiFooBundle
 */
class JungiFooBundle extends Bundle
{
	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $builder)
	{
	    /* @var \Jungi\Bundle\ThemeBundle\DependencyInjection\JungiThemeExtension $ext */
	    $ext = $builder->getExtension('jungi_theme');
        $ext->registerMappingFile(__DIR__.'/Resources/config/theme.xml', 'xml'); // with the specified loader
        $ext->registerMappingFile(__DIR__.'/Resources/config/theme.yml'); // default
	}
}
```

Load via configuration
----------------------

Loading via the configuration is exactly the same as loading via bundle extension, only way of doing it is of course
different.

```yaml
# app/config/config.yml
jungi_theme:

    # list of theme mapping files
    mappings:
        - { resource: "@JungiBooBundle/Resources/config/theme.xml", type: xml }
        # more
```

Summary
-------

And that's all :)

If your bundle is enabled in the **AppKernel** the theme(s) from the mapping file should be now loaded. You can check
it by accessing a theme source.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
