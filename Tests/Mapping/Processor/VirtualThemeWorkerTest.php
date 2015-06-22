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

use Jungi\Bundle\ThemeBundle\Mapping\Processor\VirtualThemeWorker;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * VirtualThemeWorker Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeWorkerTest extends TestCase
{
    /**
     * @var VirtualThemeWorker
     */
    private $worker;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->worker = new VirtualThemeWorker();
    }

    public function testProcess()
    {
        $registry = new ThemeDefinitionRegistry();

        $virtualTheme = new VirtualThemeDefinition(array(
            new Reference('foo_child_mobile', 'mobile'),
            new Reference('foo_december')
        ));
        $registry->registerThemeDefinition('foo', $virtualTheme);

        $firstChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_child_mobile', $firstChild);

        $secondChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_december', $secondChild);

        // Process
        $this->worker->process($registry);

        // Assert
        $this->assertEmpty($virtualTheme->getThemeReferences());
        $this->assertEquals($firstChild, $virtualTheme->getTheme('mobile'));
        $this->assertEquals($secondChild, $virtualTheme->getTheme('foo_december'));
    }

    public function testDoubledReference()
    {
        $registry = new ThemeDefinitionRegistry();

        $virtualTheme = new VirtualThemeDefinition(array(
            new Reference('foo_child_mobile', 'mobile'),
            new Reference('foo_december'),
            new Reference('foo_december', 'december')
        ));
        $registry->registerThemeDefinition('foo', $virtualTheme);

        $firstChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_child_mobile', $firstChild);

        $secondChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_december', $secondChild);

        // Process
        $this->worker->process($registry);

        // Assert
        $this->assertEquals($secondChild, $virtualTheme->getTheme('december'));
    }

    public function testManyAttachedThemes()
    {
        $registry = new ThemeDefinitionRegistry();

        $virtualTheme = new VirtualThemeDefinition(array(
            new Reference('foo_child_mobile', 'mobile'),
            new Reference('foo_december')
        ));
        $registry->registerThemeDefinition('foo', $virtualTheme);

        $virtualTheme = new VirtualThemeDefinition(array(
            new Reference('foo_december')
        ));
        $registry->registerThemeDefinition('bar', $virtualTheme);

        $firstChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_child_mobile', $firstChild);

        $secondChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_december', $secondChild);

        try {
            $this->worker->process($registry);
        } catch (\LogicException $e) {
            $this->assertStringStartsWith(
                'The theme "foo_december" is currently attached to the virtual theme "foo".',
                $e->getMessage()
            );
        }
    }

    public function testReferencedVirtualTheme()
    {
        $registry = new ThemeDefinitionRegistry();

        $virtualTheme = new VirtualThemeDefinition(array(
            new Reference('foo_child_mobile', 'mobile'),
            new Reference('foo_december')
        ));
        $registry->registerThemeDefinition('foo', $virtualTheme);

        $virtualTheme = new VirtualThemeDefinition(array(
            new Reference('foo')
        ));
        $registry->registerThemeDefinition('bar', $virtualTheme);

        $firstChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_child_mobile', $firstChild);

        $secondChild = new StandardThemeDefinition(__DIR__);
        $registry->registerThemeDefinition('foo_december', $secondChild);

        try {
            $this->worker->process($registry);
        } catch (\LogicException $e) {
            $this->assertStringStartsWith('Virtual themes cannot consists', $e->getMessage());
        }
    }
}
