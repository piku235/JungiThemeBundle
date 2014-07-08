Loading theme mapping files
===========================

If you got your theme mapping file or files you can finally load them to a theme manager. To achieve this goal you have
for use theme mapping loaders.

**NOTE**

> The load operations are done from a bundle level.

Theme mapping loaders
---------------------

In the table below you will find all theme mapping loaders:

Name | Class (default) | Service
---- | --------------- | -------
xml | Jungi\Bundle\ThemeBundle\Mapping\Loader\XmlFileLoader | jungi.theme.mapping.loader.xml
yaml | Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlFileLoader | jungi.theme.mapping.loader.yml
php | Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpFileLoader | jungi.theme.mapping.loader.php

Loading from a bundle
---------------------

First open the file where is located your bundle class e.g. *src/Foo/FooBundle/BooFooBundle.php*. After that define
the **boot** method in the bundle if you don't have it actually. Thanks to that method we're able to load themes very
easily.

### Overview

Each of the theme mapping loader service has reference to the `jungi.theme.manager` service so all load operations will
be using this theme manager service. To accomplish the loading you've got available the **load** method. This method
has only one argument responsible for the path to a theme mapping file.

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
	    $loader = $this->container->get('jungi.theme.mapping.loader.xml');
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
	    $loader = $this->container->get('jungi.theme.mapping.loader.yml');
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
	    $loader = $this->container->get('jungi.theme.mapping.loader.php');
	    $loader->load(__DIR__ . '/Resources/config/theme.php');
	}
}
```

Summary
-------

And that's it (:

If you have enabled your bundle in the **AppKernel** the theme\themes from the mapping file should be now loaded. You can
check it by accessing the theme manager.