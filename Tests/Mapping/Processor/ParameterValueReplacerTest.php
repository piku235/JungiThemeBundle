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
use Jungi\Bundle\ThemeBundle\Mapping\Container;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\ParameterValueReplacer;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ParameterValueReplacer Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParameterValueReplacerTest extends TestCase
{
    /**
     * @var ParameterValueReplacer
     */
    private $replacer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->replacer = new ParameterValueReplacer();
    }

    public function testReplacingParameters()
    {
        // Prepare
        $container = new Container();
        $container->setParameters(array(
            'foo_1' => 'nice',
        ));

        $tag = new Tag('bar', array('%foo_1%'));
        $container->registerThemeDefinition('foo', new StandardThemeDefinition(__DIR__, array($tag)));

        // Process
        $this->replacer->process($container);

        // Assert
        $expectedTag = new Tag('bar', array('nice'));

        $this->assertEquals($expectedTag, $tag);
    }

    public function testOnSimilarLookingParameter()
    {
        // Prepare
        $container = new Container();
        $container->setParameters(array(
            'foo_1' => 'nice',
        ));

        $tag = new Tag('bar', array('%foo 1%', '%foo%bar%', '%%', '%bar', 'hae%'));
        $expectedTag = clone $tag;
        $container->registerThemeDefinition('foo', new StandardThemeDefinition(__DIR__, array($tag)));

        // Process
        $this->replacer->process($container);

        // Assert
        $this->assertEquals($expectedTag, $tag);
    }

    public function testOnMissingParameter()
    {
        $container = new Container();
        $container->setParameter('existing', 'wow');

        $theme = new StandardThemeDefinition(__DIR__);
        $theme->addTag(new Tag('bar', array('%missing%')));
        $container->registerThemeDefinition('foo', $theme);

        try {
            $this->replacer->process($container);

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('The parameter "missing" can not be found.', $e->getMessage());
        }
    }
}
