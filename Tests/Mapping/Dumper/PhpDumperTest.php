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
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
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

    public function testEmptyRegistry()
    {
        $body = $this->dumper->dump(new ThemeDefinitionRegistry());

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/php/empty.php', $body);
    }

    public function testFullFilledRegistry()
    {
        $registry = new ThemeDefinitionRegistry();

        // First theme
        $registry->registerThemeDefinition('zoo_desktop', new StandardThemeDefinition('FooBundle/Resources/theme'));

        // Second theme
        $registry->registerThemeDefinition('zoo_different', new StandardThemeDefinition(
            'FooBundle/Resources/theme',
            array(
                new Tag('jungi.desktop_devices'),
                new Tag('jungi.mobile_devices', array(array('AndroidOS'), 'foo', 2, array(
                    'multi' => array(3 => 'foo', array(1))
                ))),
            )
        ));

        // Third theme
        $info = ThemeInfoEssence::createBuilder()
            ->setName('Virtual theme')
            ->setDescription('Super virtual theme')
            ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'homepage'))
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

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/php/full.php', $this->dumper->dump($registry));
    }
}