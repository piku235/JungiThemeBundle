Loading theme mapping files
===========================

After you have completed creating your theme mapping file you can finally load it to a theme manager. To achieve this goal
you have for use the following theme mapping loaders.

Format | Class (default) | Service
---- | --------------- | -------
xml | Jungi\Bundle\ThemeBundle\Mapping\Loader\XmlFileLoader | jungi_theme.mapping.loader.xml
yaml | Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlFileLoader | jungi_theme.mapping.loader.yml
php | Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpFileLoader | jungi_theme.mapping.loader.php

**NOTE**

> The load operations are done from a bundle class.

Loading from a bundle
---------------------

First open the file where is located your bundle class e.g. `src/Foo/FooBundle/BooFooBundle.php`. After that define
the **boot** method in the bundle if you don't have it actually. Thanks to that method we're able to load themes very
easily.

### Overview

Each of theme mapping loader service has reference to the `jungi_theme.manager` service so all load operations will
use this theme manager service. To accomplish the loading you've got available the **load** method. This method has only
one argument responsible for the path to a theme mapping file.

### XML Mapping Loader

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
	    $loader = $this->container->get('jungi_theme.mapping.loader.xml');
	    $loader->load(__DIR__ . '/Resources/config/theme.xml');
	}
}
```

### YAML Mapping Loader

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
	    $loader = $this->container->get('jungi_theme.mapping.loader.yml');
	    $loader->load(__DIR__ . '/Resources/config/theme.yml');
	}
}
```

### PHP Mapping Loader

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
	    $loader = $this->container->get('jungi_theme.mapping.loader.php');
	    $loader->load(__DIR__ . '/Resources/config/theme.php');
	}
}
```

Summary
-------

And that's all :)

If your bundle is enabled in the **AppKernel** the theme(s) from the mapping file should be now loaded. You can check
it by accessing a theme manager.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)