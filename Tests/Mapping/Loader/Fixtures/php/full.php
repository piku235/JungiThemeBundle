<?php

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Core\Information\Author;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Fake as FakeTag;

$info = ThemeInfoEssence::createBuilder()
    ->setName('A fancy theme')
    ->setDescription('<i>foo desc</i>')
    ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'))
    ->addAuthor(new Author('piku234', 'foo@gmail.com'))
    ->getThemeInfo();
$definition = new ThemeDefinition();
$definition
    ->setPath('@JungiFooBundle/Resources/theme')
    ->addTag(new Tag('jungi.mobile_devices', array(array('iOS', 'AndroidOS'))))
    ->addTag(new Tag('jungi.tablet_devices'))
    ->addTag(new Tag('jungi.desktop_devices'))
    ->addTag(new Tag('jungi.fake', array(FakeTag::SPECIAL)))
    ->setInfo(ThemeInfoImporter::import($info))
;
$registry->registerThemeDefinition('foo_1', $definition);

$registry->registerThemeDefinition('foo_2', new ThemeDefinition(
    '@JungiFooBundle/Resources/theme',
    array(
        new Tag('jungi.mobile_devices'),
        new Tag('jungi.tablet_devices'),
    )
));

$registry->registerThemeDefinition('foo_3', new ThemeDefinition(
    '@JungiFooBundle/Resources/theme',
    array(
        new Tag('jungi.desktop_devices'),
    )
));

$registry->registerThemeDefinition('foo_4', new ThemeDefinition('@JungiFooBundle/Resources/theme'));

$registry->registerThemeDefinition('foo_6', new VirtualThemeDefinition(array(
    new Reference('foo_4'),
)));

$info = ThemeInfoEssence::createBuilder()
    ->setName('A fancy theme')
    ->setDescription('<i>foo desc</i>')
    ->addAuthor(new Author('piku234', 'foo@gmail.com'))
    ->getThemeInfo();
$registry->registerThemeDefinition('foo_5', new VirtualThemeDefinition(
    array(
        new Reference('foo_2', 'mobile'),
        new Reference('foo_3'),
    ),
    array(
        new Tag('jungi.desktop_devices'),
        new Tag('jungi.mobile_devices'),
        new Tag('jungi.tablet_devices'),
        new Tag('jungi.fake'),
    ),
    ThemeInfoImporter::import($info)
));
