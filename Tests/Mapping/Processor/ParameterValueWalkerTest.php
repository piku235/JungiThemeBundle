<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Mapping\Container;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\ParameterValueWalker;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ParameterValueWalker Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParameterValueWalkerTest extends TestCase
{
    /**
     * @var ParameterValueWalker
     */
    private $walker;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->walker = new ParameterValueWalker();
    }

    public function testReplacingParameters()
    {
        $container = new Container();
        $container->setParameters(array(
            'foo_1' => 'nice',
            'foo_2' => array(
                'test' => 1,
                'second' => array(5.4)
            ),
            'foo_theme' => 'footheme'
        ));

        $info = ThemeInfoEssence::createBuilder()
            ->setName('%foo_theme%')
            ->setDescription('%foo_1%')
            ->addAuthor(new Author('%foo_1%', '%foo_theme%', '%foo_1%'))
            ->getThemeInfo();
        $theme = new StandardThemeDefinition(__DIR__);
        $theme->addTag(new Tag('bar', array(
            '%foo_1%',
            array(
                array('inner' => '%foo_2%')
            )
        )));
        $theme->setInformation(ThemeInfoImporter::import($info));
        $container->registerThemeDefinition('foo', $theme);

        $this->walker->process($container);

        $expectedInfo = ThemeInfoEssence::createBuilder()
            ->setName('footheme')
            ->setDescription('nice')
            ->addAuthor(new Author('nice', 'footheme', 'nice'))
            ->getThemeInfo();
        $expectedTag = new Tag('bar', array(
            'nice',
            array(
                array('inner' => array(
                    'test' => 1,
                    'second' => array(5.4)
                ))
            )
        ));

        $this->assertEquals($expectedTag, reset($theme->getTags()));
        $this->assertEquals(ThemeInfoImporter::import($expectedInfo), $theme->getInformation());
    }

    public function testOnInvalidParameter()
    {
        $container = new Container();
        $container->setParameter('existing', 'wow');

        $theme = new StandardThemeDefinition(__DIR__);
        $theme->addTag(new Tag('bar', array('%missing%')));
        $container->registerThemeDefinition('foo', $theme);

        try {
            $this->walker->process($container);

            $this->fail('RuntimeException should be thrown.');
        } catch (\RuntimeException $e) {
            $this->assertEquals('The parameter "missing" can not be found.', $e->getMessage());
        }
    }
}
