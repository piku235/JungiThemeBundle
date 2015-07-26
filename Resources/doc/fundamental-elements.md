Fundamental elements
====================

Theme
-----

Let's start with defining a theme:

> A theme is a collection of templates and some other resources like images, stylesheets, javascripts that have an 
> influence to the look of a page.

Every theme in the JungiThemeBundle is an object of the `Jungi\Bundle\ThemeBundle\Core\ThemeInterface`. Thanks to this 
interface we can easily manipulate themes and obtain important for us information.

```php
interface ThemeInterface
{
    /**
     * Returns the unique theme name
     *
     * A theme name should be as simple as possible e.g. "footheme", "bar-theme".
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the theme tag collection
     *
     * @return \Jungi\Bundle\ThemeBundle\Tag\TagCollection
     */
    public function getTags();

    /**
     * Returns the absolute path to the theme directory
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the information about the theme
     *
     * @return \Jungi\Bundle\ThemeBundle\Information\ThemeInfo
     */
    public function getInfo();
}
```

To create a new theme you will not use a pure object, but one of the available theme mappings: xml, yaml or php. [Click here](https://github.com/piku235/JungiThemeBundle/tree/master/Resources/doc/index.md#theme-mappings)
to find out how do that. Also if you want to know where themes are stored [go here](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/themes-and-templates.md#themes-locations).

### VirtualTheme

A virtual theme may be a bit confusing, but this is a sort of theme that does not really exist, so it has not got any assets 
and view files. It only combines together similar themes and acts as a representative of these themes. Only one of the 
subordinate themes will be used and which one of them will be decided by virtual theme resolver. 

The interface of virtual theme is following:

```php
interface VirtualThemeInterface extends ThemeInterface
{
    /**
     * Sets a theme which will be used by the virtual theme.
     *
     * @param string|ThemeInterface $pointed A theme name or a theme instance
     *
     * @throws \InvalidArgumentException If the passed theme has a wrong type
     * @throws ThemeNotFoundException If the given theme does not belongs to the virtual theme
     */
    public function setPointedTheme($pointed);

    /**
     * Returns the parent theme.
     *
     * @return ThemeInterface
     */
    public function getPointedTheme();

    /**
     * Returns the child themes of the virtual theme.
     *
     * @return ThemeCollection
     */
    public function getThemes();
}
```

#### For what do you need it?

A virtual theme can be used for various things. The easiest example is an adaptive theme (AWD) - it is usually combined 
of two or more themes where each one of them has a tag that determines their purpose. 

Suppose that we have an adaptive theme with two sub themes where the first one is for mobile devices and the second one 
is for desktop devices. Thanks to virtual themes we do not need to create two separate themes and do crazy stuff to get 
this working, we just need to create a virtual theme that will hold these two themes together and choose one of them at 
appropriate time. 

The power of virtual themes not ends on AWD. They can be used for everything like e.g. we have four themes where each one 
is designed for different season and so on - ideas can be infinite.

#### How about tags?

When you decide to make a virtual theme an obvious thing is that subordinate themes will have some tags that describes 
them. But wait, what about a virtual theme? Itself it does not have any tags, although the subordinate themes have them. 
As this is a virtual theme it should as best to imitate a real theme, so leaving a virtual theme without any tags is not 
right. I was thinking about to write a component that will include tags from subordinate themes to a virtual theme, but 
there could be a problem with merging tags that are the same. It turned out that the best solution and as well as a good 
rule is to manage virtual theme tags by yourself, at least at this moment. This means that you have to decide which tags 
of subordinate themes you want to include and how to merge tags of the same type.

ThemeInfo
---------

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Information/ThemeInfo.php)

In some cases you would like to get some information about a theme in order to show these information to a user. 
Information can be easily stored in an object. In the JungiThemeBundle such an object must come from a class that inherits 
the `Jungi\Bundle\ThemeBundle\Information\ThemeInfo` abstract class which acts as the interface.

```php
abstract class ThemeInfo
{
    /**
     * Returns the friendly theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the authors
     *
     * @return AuthorInterface[]
     */
    public function getAuthors();

    /**
     * Returns the version
     *
     * @return string|null
     */
    public function getVersion();

    /**
     * Returns the description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Returns the license type
     *
     * @return string|null
     */
    public function getLicense();
}
```

**NOTE**

> The method **getName** of the ThemeInfo should always return a value

### ThemeInfoEssence

[Show the class](https://github.com/piku235/JungiThemeBundle/blob/master/Information/ThemeInfoEssence.php)

Due to a large number of properties implementing them in the constructor seems to be a bad idea, because it would only 
bring a mess in its signature. Also setter methods are not a good idea, because after an object creation there will be 
still a possibility for changing the object properties and that should not be possible. Finally I came to conclusion 
to create a simple builder which is strictly associated with the class. The builder provides setter methods with the 
fluent interface support.

Here is the example of creating new instance of the **ThemeInfoEssence**:

```php
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Information\Author;

$ib = ThemeInfoEssence::createBuilder();
$ib
    ->setName('A simple theme')
    ->setDescription('a simple theme with the beautiful design')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'foo.com'));

// Builds the new ThemeInfoEssence instance
$info = $ib->getThemeInfo();
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)
