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
$ php composer.phar require jungi/theme-bundle "~1.0"
```

### Step 2: Enable the bundle

Enable the bundle in the AppKernel:

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
of theme resolvers. You can use one of these theme resolvers or use your own theme resolver by setting symfony service.

The bundle contains the following theme resolvers:

Type | Class
---- | -----
cookie | CookieThemeResolver
in_memory | InMemoryThemeResolver
session | SessionThemeResolver

#### Setup primary theme resolver

##### CookieThemeResolver

The CookieThemeResolver uses cookies for holding the theme name. It takes only one argument which is of array type and
it's responsible for standard cookie options.

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

##### InMemoryThemeResolver

The InMemoryThemeResolver is a very simple theme resolver which holds the theme name in the class variable. It accepts two
arguments where the first one takes a theme name and the second one decides whether the stored theme name can be changed
or not. The second argument was only introduced for tests benefits. This theme resolver can be used as the default theme
resolver.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            type: in_memory
            arguments: foo_theme # a theme name
```

##### SessionThemeResolver

The SessionThemeResolver uses the session mechanism for holding the theme name. It doesn't take any arguments, so the
`arguments` doesn't must be provided.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            type: session
```

##### Theme resolver service

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

#### Setup fallback theme resolver (optional)

A fallback theme resolver is very helpful when a primary theme resolver doesn't match any theme for the request. To setup
a fallback theme resolver you must follow the same steps as for the primary theme resolver.

```yml
# app/config/config.yml
jungi_theme:
    resolver:
        fallback:
            # the rest things
```

**NOTE**

> Remember one thing about setting a fallback theme resolver. The set fallback theme resolver should always return
> a theme name for the request

Final
-----

So if you completed the installation you can from now on start using the bundle and learn super things which I have described
in the documentation :)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/index.md)
