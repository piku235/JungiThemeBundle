<?php

$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\Theme(
    'zoo_desktop',
    'FooBundle/Resources/theme',
    \Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence::createBuilder()
        ->setName('FooTheme')
        ->setDescription('Super theme')
        ->addAuthor(new \Jungi\Bundle\ThemeBundle\Information\Author('piku235', 'piku235@gmail.com', 'homepage'))
        ->getThemeInfo(),
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection()
));

return $collection;