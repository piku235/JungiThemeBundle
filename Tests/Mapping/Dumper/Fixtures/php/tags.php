<?php

$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\Theme(
    'foo',
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

return $collection;