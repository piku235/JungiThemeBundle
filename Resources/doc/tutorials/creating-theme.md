Creating a theme
================

The goal of this tutorial is to show you how from scratch create a simple theme and how to use it. Let's assume that 
we are gonna to create a responsive theme (RWD) which will be used on every page of our website.

### Step 1: Create a bundle for the theme

The first thing that we have to do is to create a "container" in which our theme will be placed. For this tutorial 
I chose a bundle as container, although you can also choose a project root as container. Suppose that the created 
bundle will be called **JungiHeroThemeBundle** or whatever you want. The following directory structure of the bundle is 
enough for our theme:

```
src/
    Jungi/
        HeroThemeBundle/
            DependencyInjection/
                Configuration.php
                JungiHeroThemeExtension.php
            Resources/
                public/
                    js/
                    css/
                        styles.css
                theme/
                    layout.html.twig
                    SonataAdminBundle/
                        standard_layout.html.twig
                    SonataUserBundle/
                        Registration/
                            register_content.html.twig
                        Security/
                            login.html.twig
            JungiHeroThemeBundle.php
```

As you see the theme has got own template `layout.html.twig` and also overrides some templates in the **SonataAdminBundle**
and the **SonataUserBundle**. Overriding bundle templates by themes is quite simple, it works exactly the same as 
overriding bundle templates by your project resources. I placed here the bundles created by the **Sonata Project** only 
to show you that you can override each bundle template that you want.

As the final thing in this step we only must activate the created bundle in the `app/AppKernel.php`. 

### Step 2: Decide which theme mapping to use

After we have our theme directory created we have to define our theme, so that the JungiThemeBundle will know that this
theme directory is not a some random directory. To define a theme you will use one of these three various theme mappings: 
**xml**, **yaml** and **php**. For this tutorial I chose the xml theme mapping. 

The theme mapping file for this theme can look like below:

```xml
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <themes>
        <theme name="jungi_hero" path="@JungiHeroThemeBundle/Resources/theme">
            <tags>
                <tag name="jungi.mobile_devices" />
                <tag name="jungi.tablet_devices" />
                <tag name="jungi.desktop_devices" />
            </tags>
        </theme>
    </themes>

</theme-mapping>
```

We can save this theme mapping file into the `Resources/config` directory as the `theme.xml`.

### Step 3: Loading the created theme mapping file

Now we must notify the JungiThemeBundle about our theme, so that it will be available in the theme source. To do this we 
can load the created theme mapping file using the JungiThemeBundle extension. This will be performed from our bundle.

Finally the bundle class should looks like below:

```php
<?php
// src/Jungi/HeroThemeBundle/JungiHeroThemeBundle.php
namespace Jungi\HeroThemeBundle;

use Jungi\Bundle\ThemeBundle\DependencyInjection\JungiThemeExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * JungiHeroThemeBundle
 */
class JungiHeroThemeBundle extends Bundle
{
	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $builder)
	{
	    $ext = $builder->getExtension('jungi_theme');
        $ext->registerMappingFile(__DIR__.'/Resources/config/theme.xml');
	}
}
```

And that is almost the end. After this step the theme should be available in the theme source.

### Step 4: Set the theme for a theme resolver

To set our theme to be visible on every page we can use the **InMemoryThemeResolver**. 

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            in_memory: jungi_hero
```

That is all. From now on the theme should be visible on every page. Thanks for your attention and have a nice further fun 
with the bundle. :)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
