Installation
============

The installation process is very quick, as you see only 3 steps.

Let's begin
-----------

### Step 1: Install JungiThemeBundle using composer

Add the JungiThemeBundle in your composer.json:

```js
{
    "require": {
        "jungi/theme-bundle": "dev-master"
    }
}
```

Or run the following command in your project:

```bash
$ php composer.phar require jungi/theme-bundle "dev-master"
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Jungi\Bundle\ThemeBundle\JungiThemeBundle(),
    );
}
```

### Step 3: Configuration

Finally you have to choose the theme resolver that the JungiThemeBundle will use. The bundle comes with the default set
of theme resolvers. You can use one of these theme resolvers or use a own theme resolver by setting a symfony service.

#### Setup a built-in theme resolver

Type | Class
---- | -----
cookie | CookieThemeResolver
in_memory | InMemoryThemeResolver
session | SessionThemeResolver

If you have chosen one of the above theme resolvers then define the configuration like below:

```yml
# app/config/config.yml

jungi_theme:
    resolver:
        primary:
            type: # a theme resolver type
            arguments: # arguments which will be passed to a theme resolver, optional
```

#### Setup a theme resolver service

To register a theme resolver service you have to define the configuration like below:

```yml
# app/config/config.yml

jungi_theme:
    resolver:
        primary:
            id: # a theme resolver service id
```

Or the shorthand version:

```yml
# app/config/config.yml

jungi_theme:
    resolver:
        primary: # a theme resolver service id
```

**NOTE**

> In this step I have only presented the minimal configuration. For more go to the [Configuration](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/configuration.md)
> chapter.

Final
-----

So if you completed the installation you can from now on start using the bundle and learn super things which I have described
in the documentation (:

[Back to index](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/index.md)
