Responsive + Server-Side (RESS)
-------------------------------

Just like I said in the main documentation the RESS is nothing else than RWD. So to create a theme you must follow the
same steps like in the [RWD](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/rwd.md) chapter. In
this chapter I will show the tools which are the main goal of the RESS.

DeviceHelper
------------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Helper/DeviceHelper.php)

The DeviceHelper is a simple class with the friendly API which is designed for PHP templates. The class provides the following
methods: **isDesktop**, **isMobile**, **isTablet** and **isDevice**. Only the **isDevice** method takes one argument to
which you must pass a value according to the accepted values by the method **is** of the MobileDetect class.

### Example

```php
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php $view['slots']->output('title', 'Title') ?></title>
</head>
<body>
    <h1 style="text-align: center">Super homepage</h1>
    <?php if ($view['slots']->has('body')): ?>
        <?php $view['slots']->output('body') ?>
    <?php else: ?>
        <?php if ($view['device']->isMobile()): ?>
            <p>Oooh, you're using a mobile device.</p>
            <?php if ($view['device']->isDevice('iOS')): ?>
                <p>Are your device running the iOS?</p>
            <?php endif ?>
        <?php endif ?>
    <?php endif ?>
</body>
</html>
```

DeviceExtension
---------------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Twig/Extension/DeviceExtension.php)

The DeviceExtension is the same as the DeviceHelper with one difference. It's designed for Twig templates. The DeviceExtension
provides the following twig functions: **is_desktop**, **is_mobile**, **is_tablet** and **is_device**.

### Example

```html
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{% block title %}Title{% endblock %}</title>
</head>
<body>
    <h1 style="text-align: center">Super homepage</h1>
    {% block body %}
        {% if is_mobile() %}
            <p>Oooh, you're using a mobile device.</p>
            {% if is_device('iOS') %}
                <p>Are your device running the iOS?</p>
            {% endif %}
        {% endif %}
    {% endblock %}
</body>
</html>
```

@TODO
-----

* Minimization of images - I know that I have mentioned in the main documentation the example about generating images in
appropriate dimensions, but this thing I plan to do when the bundle will be used by the majority of people.

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)