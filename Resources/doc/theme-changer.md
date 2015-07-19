Theme changer
=============

As you can easily guess a theme changer allows you to change a current theme. It has been created to ease and to centralize
change of a current theme. Otherwise you would use a theme resolver to change the current theme.

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

A theme changer is a very simple due to its small interface, so there would not be (fortunately) too much to read. 
It is available under the `jungi_theme.changer` service.

Example
-------

This example shows a frequent situation when you are going to change the current theme by form.

```php
$form = $this->createForm(new ThemeType());
$form->handleRequest($request);

if ($form->isValid()) {
    $data = $form->getData();
    $this->get('jungi_theme.changer')->change($data['theme'], $request);
}
```