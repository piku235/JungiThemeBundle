Theme changer
=============

A theme changer was created to ease and to centralize process of changing the current theme. Otherwise you would use 
directly a theme resolver to change the current theme.

A theme changer implements the `Jungi\Bundle\ThemeBundle\Changer\ThemeChangerInterface`.

```php
interface ThemeChangerInterface
{
    /**
     * Changes the current theme with a new one.
     *
     * @param string|ThemeInterface $theme   A theme name or a theme instance
     * @param Request               $request A Request instance
     */
    public function change($theme, Request $request);
}
```

The `Jungi\Bundle\ThemeBundle\Changer\ThemeChanger` is the default theme changer which implements only basic methods
contained in the interface. You can access a theme changer via the `jungi_theme.changer` service. 

Example
-------

The example below shows a frequent situation when you want to change the current theme by using a form.

```php
$themeChanger = $this->get('jungi_theme.changer');
$form = $this->createForm(new ThemeType());
$form->handleRequest($request);

if ($form->isValid()) {
    $data = $form->getData();
    $themeChanger->change($data['theme'], $request);
}
```

[Back to the documentation](https://github.com/piku235/JungiThemeBundle/blob/master/Resources/doc/index.md)