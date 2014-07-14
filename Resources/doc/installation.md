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

Finally you have to choose a theme resolver that the JungiThemeBundle will use. The bundle comes with the default set
of theme resolvers. You can use one of these theme resolvers or use your own theme resolver by setting a symfony service.

#### Setup a built-in theme resolver

The bundle contains the following theme resolvers:

Type | Class
---- | -----
cookie | CookieThemeResolver
in_memory | InMemoryThemeResolver
session | SessionThemeResolver

##### Cookie

Cookie theme resolver takes only one argument which is of array type and it's responsible for cookie options.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            type: cookie
            arguments:
                - lifetime: 3600 # time in sec
                  path: /
                  domain: ~
                  secure: false
                  httpOnly: true
```

##### InMemory

InMemory theme resolver accepts two arguments where the first one takes a theme name and the second one decides whether
the theme name can be changed or not. The second argument was only introduced for tests benefits.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            type: in_memory
            arguments: foo_theme # a theme name
```

##### Session

Session theme resolver doesn't has any arguments, so the `arguments` option don't must be provided.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            type: session
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
