<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Dumper;

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Mapping\Dumper\PhpDumper;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Mapping\FakeThemeDefinition;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * PhpDumper Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpDumperTest extends TestCase
{
    /**
     * @var PhpDumper
     */
    private $dumper;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dumper = new PhpDumper($this->createTagClassRegistry());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDumpOnNonSupportedThemeDefinition()
    {
        $registry = new ThemeDefinitionRegistry();
        $registry->registerThemeDefinition('non_supported', new FakeThemeDefinition());
        $this->dumper->dump($registry);
    }

    public function testDumpOnEmptyRegistry()
    {
        $body = $this->dumper->dump(new ThemeDefinitionRegistry());

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/php/empty.php', $body);
    }

    public function testDumpTags()
    {
        $registry = new ThemeDefinitionRegistry();

        // Second theme
        $registry->registerThemeDefinition('foo', new StandardThemeDefinition(
            'FooBundle/Resources/theme',
            array(
                new Tag('jungi.desktop_devices'),
                new Tag('jungi.mobile_devices', array(array('AndroidOS'), 'foo', 2, array(
                    'multi' => array(3 => 'foo', array(1))
                ))),
            )
        ));

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/php/tags.php', $this->dumper->dump($registry));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOnNonExistingTag()
    {
        $registry = new ThemeDefinitionRegistry();
        $registry->registerThemeDefinition('zoo_different', new StandardThemeDefinition(
            'FooBundle/Resources/theme',
            array(
                new Tag('jungi.non_exist')
            )
        ));
        $this->dumper->dump($registry);
    }

    public function testDumpOnVirtualTheme()
    {
        $registry = new ThemeDefinitionRegistry();

        // First theme
        $registry->registerThemeDefinition('zoo_desktop', new StandardThemeDefinition('FooBundle/Resources/theme'));

        // Second theme
        $registry->registerThemeDefinition('zoo_different', new StandardThemeDefinition(
            'FooBundle/Resources/theme',
            array(
                new Tag('jungi.desktop_devices'),
                new Tag('jungi.mobile_devices', array(array('AndroidOS'))),
            )
        ));

        // Third theme
        $info = ThemeInfoEssence::createBuilder()
            ->setName('Virtual theme')
            ->getThemeInfo();
        $definition = new VirtualThemeDefinition();
        $definition->addTag(new Tag('jungi.desktop_devices'));
        $definition->setInfo(ThemeInfoImporter::import($info));
        $definition->addTheme('normal', new StandardThemeDefinition(
            'FooBundle/Resources/theme',
            array(
                new Tag('jungi.desktop_devices'),
            )
        ));
        $definition->addTheme('mobile', new StandardThemeDefinition(
            'FooBundle/Resources/theme',
            array(
                new Tag('jungi.mobile_devices', array(array('iOS', 'AndroidOS'))),
            )
        ));
        $registry->registerThemeDefinition('zoo_virtual', $definition);

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/php/virtual_theme.php', $this->dumper->dump($registry));
    }

    public function testDumpOnVirtualThemeWithoutChildren()
    {
        $registry = new ThemeDefinitionRegistry();
        $registry->registerThemeDefinition('zoo_virtual', new VirtualThemeDefinition());

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/php/virtual_theme_without_children.php', $this->dumper->dump($registry));
    }

    public function testDumpOnFullThemeInfo()
    {
        $registry = new ThemeDefinitionRegistry();

        $info = ThemeInfoEssence::createBuilder()
            ->setName('FooTheme')
            ->setDescription('Super theme')
            ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'homepage'))
            ->getThemeInfo();
        $def = new StandardThemeDefinition('FooBundle/Resources/theme');
        $def->setInfo(ThemeInfoImporter::import($info));
        $registry->registerThemeDefinition('zoo_desktop', $def);

        $this->assertStringEqualsFile(__DIR__ . '/Fixtures/php/themeinfo_full.php', $this->dumper->dump($registry));
    }

    public function testDumpOnEmptyThemeInfo()
    {
        $registry = new ThemeDefinitionRegistry();

        $def = new StandardThemeDefinition('FooBundle/Resources/theme');
        $def->setInfo(new ThemeInfo());
        $registry->registerThemeDefinition('zoo_desktop', $def);

        $this->assertStringEqualsFile(__DIR__ . '/Fixtures/php/themeinfo_empty.php', $this->dumper->dump($registry));
    }

    public function testDumpThemeInfoWithRequiredFields()
    {
        $registry = new ThemeDefinitionRegistry();

        $info = ThemeInfoEssence::createBuilder()
            ->setName('FooTheme')
            ->getThemeInfo();
        $def = new StandardThemeDefinition('FooBundle/Resources/theme');
        $def->setInfo(ThemeInfoImporter::import($info));
        $registry->registerThemeDefinition('zoo_desktop', $def);

        $this->assertStringEqualsFile(__DIR__ . '/Fixtures/php/themeinfo_simple.php', $this->dumper->dump($registry));
    }

    public function testDumpThemeInfoWithMultipleAuthors()
    {
        $registry = new ThemeDefinitionRegistry();

        $info = ThemeInfoEssence::createBuilder()
            ->setName('FooTheme')
            ->addAuthor(new Author('piku235', 'piku235@gmail.com'))
            ->addAuthor(new Author('piku234', 'jungi@gmail.com', 'foo.com'))
            ->getThemeInfo();
        $def = new StandardThemeDefinition('FooBundle/Resources/theme');
        $def->setInfo(ThemeInfoImporter::import($info));
        $registry->registerThemeDefinition('zoo_desktop', $def);

        $this->assertStringEqualsFile(__DIR__ . '/Fixtures/php/themeinfo_authors.php', $this->dumper->dump($registry));
    }
}