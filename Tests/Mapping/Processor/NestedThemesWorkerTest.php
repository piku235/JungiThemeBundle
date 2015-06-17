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

use Jungi\Bundle\ThemeBundle\Mapping\Processor\NestedThemesWorker;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * NestedThemesWorker Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class NestedThemesWorkerTest extends TestCase
{
    /**
     * @var NestedThemesWorker
     */
    private $worker;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->worker = new NestedThemesWorker();
    }

    public function testProcess()
    {
        $registry = new ThemeDefinitionRegistry();
        $registry->registerThemeDefinition('desktop', new StandardThemeDefinition(__DIR__));

        $virtualTheme = new VirtualThemeDefinition();
        $virtualTheme->addTheme('foo_mobile', new StandardThemeDefinition(__DIR__));
        $virtualTheme->addTheme('desktop', new StandardThemeDefinition(__DIR__));
        $registry->registerThemeDefinition('foo_virtual', $virtualTheme);

        // Process
        $this->worker->process($registry);

        // Assert
        $this->assertCount(4, $registry->getThemeDefinitions());
        $this->assertCount(2, $virtualTheme->getThemeReferences());
        $this->assertEmpty($virtualTheme->getThemes());

        $themeNames = array('desktop', 'foo_mobile');
        $themeRefs = array_map(function (Reference $ref) {
            return $ref->getAlias();
        }, $virtualTheme->getThemeReferences());
        $this->assertEmpty(array_diff($themeNames, $themeRefs));
    }
}
