<?php

$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\Theme(
    'zoo_desktop',
    'FooBundle/Resources/theme',
    \Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence::createBuilder()
        ->getThemeInfo(),
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection()
));

return $collection;