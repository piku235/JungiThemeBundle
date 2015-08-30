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
    \Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence::createBuilder()
        ->setName('Virtual theme')
        ->getThemeInfo(),
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection(array(
        new Jungi\Bundle\ThemeBundle\Tag\DesktopDevices()
    ))
));

return $collection;