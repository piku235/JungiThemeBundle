<?php

$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$collection->add(new \Jungi\Bundle\ThemeBundle\Core\Theme(
    'zoo_desktop',
    'FooBundle/Resources/theme',
    \Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence::createBuilder()
        ->setName('FooTheme')
        ->addAuthor(new \Jungi\Bundle\ThemeBundle\Information\Author('piku235', 'piku235@gmail.com'))
        ->addAuthor(new \Jungi\Bundle\ThemeBundle\Information\Author('piku234', 'jungi@gmail.com', 'foo.com'))
        ->getThemeInfo(),
    new \Jungi\Bundle\ThemeBundle\Tag\TagCollection()
));

return $collection;