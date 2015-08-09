<?php

$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\Theme(
    'zoo_desktop',
    'FooBundle/Resources/theme',
    null,
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection()
));
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\Theme(
    'zoo_different',
    'FooBundle/Resources/theme',
    null,
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection(array(
        new Jungi\Bundle\ThemeBundle\Tag\DesktopDevices(),
        new Jungi\Bundle\ThemeBundle\Tag\MobileDevices(array(
            0 => 'AndroidOS'
        ), 'foo', 2, array(
            'multi' => array(
                3 => 'foo',
                4 => array(
                    0 => 1
                )
            )
        ))
    ))
));
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\VirtualTheme(
    'zoo_virtual',
    array(
        new \Jungi\Bundle\ThemeBundle\Core\Theme(
            'normal',
            'FooBundle/Resources/theme',
            null,
            new \Jungi\Bundle\ThemeBundle\Tag\TagCollection(array(
                new Jungi\Bundle\ThemeBundle\Tag\DesktopDevices()
            ))
        ), new \Jungi\Bundle\ThemeBundle\Core\Theme(
            'mobile',
            'FooBundle/Resources/theme',
            null,
            new \Jungi\Bundle\ThemeBundle\Tag\TagCollection(array(
                new Jungi\Bundle\ThemeBundle\Tag\MobileDevices(array(
                    0 => 'iOS',
                    1 => 'AndroidOS'
                ))
            ))
        )
    ),
    \Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence::createBuilder()
        ->setName('Virtual theme')
        ->setDescription('Super virtual theme')
        ->addAuthor(new \Jungi\Bundle\ThemeBundle\Information\Author('piku235', 'piku235@gmail.com', 'homepage'))
        ->getThemeInfo(),
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection(array(
        new Jungi\Bundle\ThemeBundle\Tag\DesktopDevices()
    ))
));

return $collection;