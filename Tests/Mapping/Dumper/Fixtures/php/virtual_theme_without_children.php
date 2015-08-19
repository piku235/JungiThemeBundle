<?php

$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\VirtualTheme(
    'zoo_virtual',
    array(),
    null,
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection()
));

return $collection;