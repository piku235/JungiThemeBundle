Creating a theme
================

The goal of this tutorial is to show you how from the scratch create a simple theme and how to use it. Let's assume that 
we're gonna to create a responsive theme (RWD) which will be used on every page of our website.

### Step 1: Create a bundle for the theme

The first thing that we have to do is to create a "container" in which our theme will be placed. And the most suitable
"container" is nothing else but a bundle, so we must create it. Suppose that the created bundle will be called 
**JungiHeroThemeBundle** or whatever you want. The following directory structure of the bundle is enough for our theme:

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
and the **SonataUserBundle**. Thanks to that you don't have to change sonata templates in the configuration, the theme
itself will take care of these overridden templates. I just placed here the bundles created by the **Sonata Project** 
only as an example to show you that you can override each bundle that you use in a project. I will don't show here how 
each theme template looks inside, because that's unnecessary.

As the final thing in this step we only must activate the created bundle in the `app/AppKernel.php`. 

### Step 2: Decide which theme mapping to use

After we have our theme we must define it so that the JungiThemeBundle could recognize it. To define a theme you have for 
use three various theme mappings: xml, yaml and php. For this tutorial I chose the xml theme mapping. The file of this 
theme mapping for this theme can looks like below:

```xml
<?xml version="1.0" encoding="utf-8" ?>
<theme-mapping xmlns="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://piku235.github.io/JungiThemeBundle/schema/theme-mapping https://raw.githubusercontent.com/piku235/JungiThemeBundle/master/Mapping/Loader/schema/theme-1.0.xsd">

    <themes>
        <theme name="jungi_hero" path="@JungiHeroThemeBundle/Resources/theme">
            <tags>
                <tag name="jungi.mobile_devices" />
                <tag name="jungi.desktop_devices" />
            </tags>
            <info>
                <property key="authors" type="collection">
                    <property type="collection">
                        <property key="name">piku235</property>
                        <property key="email">piku235@gmail.com</property>
                        <property key="homepage">www.foo.com</property>
                    </property>
                    <property type="collection">
                        <property key="name">piku234</property>
                        <property key="email">foo@gmail.com</property>
                        <property key="homepage">www.boo.com</property>
                    </property>
                </property>
                <property key="description"><![CDATA[<i>foo desc</i>]]></property>
                <property key="version">1.0.0</property>
                <property key="name">A fancy theme</property>
                <property key="license">MIT</property>
            </info>
        </theme>
    </themes>

</theme-mapping>
```

We can save this theme mapping file into `Resources/config` as `theme.xml`.

### Step 3: Loading the created theme mapping file

Now that the JungiThemeBundle could notice our theme we must load the created theme mapping file. We can achieve that by
using a theme mapping loader which will load all themes contained in a theme mapping file to a theme manager. We're gonna 
to use the xml theme mapping loader in the method `boot` of the bundle.

Finally the bundle class should looks like below:

```php
<?php
// src/Jungi/HeroThemeBundle/JungiHeroThemeBundle.php
namespace Jungi\HeroThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * JungiHeroThemeBundle
 */
class JungiHeroThemeBundle extends Bundle
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

And that's almost the end. After this step the theme should be available in a theme manager.

### Step 4: Set the theme for a theme resolver

To set our theme to be visible on every page we can use the `InMemoryThemeResolver`. We only have to set the theme name 
and the type of theme resolver in the configuration.

```yaml
# app/config/config.yml
jungi_theme:
    resolver:
        primary:
            type: in_memory
            arguments: jungi_hero
```

That's all. From now on the theme should be visible on every page. Thanks for your attention and have a nice further fun 
with the bundle :)

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)