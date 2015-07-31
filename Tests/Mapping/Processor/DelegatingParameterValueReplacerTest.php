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
use Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\DelegatingParameterValueReplacer;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * DelegatingParameterValueReplacer Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DelegatingParameterValueReplacerTest extends TestCase
{
    /**
     * @var DelegatingParameterValueReplacer
     */
    private $replacer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->replacer = new DelegatingParameterValueReplacer();
    }

    public function testReplacingParameters()
    {
        // Prepare
        $container = new ParametricThemeDefinitionRegistry();
        $container->setParameters(array(
            'foo_1' => 'nice',
            'path' => __DIR__,
        ));

        $tag = new Tag('bar', array('%foo_1%'));
        $theme = new StandardThemeDefinition('%path%/Resources/theme', array($tag));
        $container->registerThemeDefinition('foo', $theme);

        // Process
        $this->replacer->process($container);

        // Assert
        $expectedTag = new Tag('bar', array('nice'));
        $expectedPath = __DIR__.'/Resources/theme';

        $this->assertEquals($expectedTag, $tag);
        $this->assertEquals($expectedPath, $theme->getPath());
    }
}
