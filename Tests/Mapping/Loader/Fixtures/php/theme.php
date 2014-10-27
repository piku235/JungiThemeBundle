<?php

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Own;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;

$ib = ThemeInfoEssence::createBuilder();
$ib
    ->setName('A fancy theme')
    ->setVersion('1.0.0')
    ->setDescription('<i>foo desc</i>')
    ->setLicense('MIT')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'))
    ->addAuthor(new Author('piku234', 'foo@gmail.com', 'www.boo.com'))
;
$manager->addTheme(new Theme(
    'foo_1',
    $locator->locate('@JungiFooBundle/Resources/theme'),
    $ib->getThemeInfo(),
    new TagCollection(array(
        new Tag\DesktopDevices(),
        $tagFactory->create('jungi.mobile_devices', array(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE)),
        new Own('test')
    ))
));
