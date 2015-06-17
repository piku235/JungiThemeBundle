<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Loader;

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\Processor;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagClassRegistry;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Fake as FakeTag;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * DefinitionLoaderTest Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class DefinitionLoaderTest extends TestCase
{
    /**
     * @var ThemeDefinitionRegistry
     */
    protected $registry;

    /**
     * @var TagClassRegistry
     */
    protected $tagRegistry;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->registry = new ThemeDefinitionRegistry();
        $this->processor = new Processor($this->createTagClassRegistry(), $this->createFileLocator(__DIR__));
    }

    public function testFull()
    {
        $this->loadFile('full');

        $secondaryAuthor = new Author('piku234', 'foo@gmail.com');
        $ibuilder = ThemeInfoEssence::createBuilder();
        $ibuilder
            ->setName('A fancy theme')
            ->setDescription('<i>foo desc</i>')
            ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'))
            ->addAuthor($secondaryAuthor)
        ;
        $info1 = ThemeInfoImporter::import($ibuilder->getThemeInfo());

        $ibuilder = ThemeInfoEssence::createBuilder();
        $ibuilder
            ->setName('A fancy theme')
            ->setDescription('<i>foo desc</i>')
            ->addAuthor($secondaryAuthor)
        ;
        $info3 = ThemeInfoImporter::import($ibuilder->getThemeInfo());

        $themes = array(
            'foo_1' => new StandardThemeDefinition(__DIR__.'/Fixtures/FakeBundle/Resources/theme', array(
                new Tag('jungi.mobile_devices', array(array('iOS', 'AndroidOS'))),
                new Tag('jungi.tablet_devices'),
                new Tag('jungi.desktop_devices'),
                new Tag('jungi.fake', array(FakeTag::SPECIAL)),
            ), $info1),
        );
        $themes['foo_5'] = new VirtualThemeDefinition();
        $themes['foo_5']->setInformation($info3);
        $themes['foo_5']->setTags(array(
            new Tag('jungi.desktop_devices'),
            new Tag('jungi.mobile_devices'),
            new Tag('jungi.tablet_devices'),
            new Tag('jungi.fake'),
        ));
        $themes['foo_5']->addTheme('mobile', new StandardThemeDefinition(__DIR__.'/Fixtures/FakeBundle/Resources/theme', array(
            new Tag('jungi.mobile_devices'),
            new Tag('jungi.tablet_devices'),
        )));
        $themes['foo_5']->addTheme('foo_3', new StandardThemeDefinition(__DIR__.'/Fixtures/FakeBundle/Resources/theme', array(
            new Tag('jungi.desktop_devices'),
        )));

        $themes['foo_6'] = new VirtualThemeDefinition();
        $themes['foo_6']->addTheme('foo_4', new StandardThemeDefinition(__DIR__.'/Fixtures/FakeBundle/Resources/theme'));

        foreach ($themes as $name => $theme) {
            $this->assertEquals($theme, $this->registry->getThemeDefinition($name));
        }
    }

    /**
     * @param $path
     *
     * @return FileLocator
     */
    protected function createFileLocator($path)
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel
            ->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(__DIR__.'/Fixtures/FakeBundle/Resources/theme'))
        ;

        return new FileLocator($kernel, $path);
    }

    /**
     * Loads the given file.
     *
     * @param string $file A file without ext
     */
    abstract protected function loadFile($file);
}
