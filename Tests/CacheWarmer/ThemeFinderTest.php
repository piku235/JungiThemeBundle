<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\CacheWarmer;

use Jungi\Bundle\ThemeBundle\CacheWarmer\ThemeFinder;
use Jungi\Bundle\ThemeBundle\Core\ThemeFilenameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ThemeFinder Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeFinderTest extends TestCase
{
    public function testFind()
    {
        $manager = new ThemeManager(array(
            $this->createThemeMock('foo', __DIR__ . '/Fixtures/FooThemeBundle/Resources/theme'),
            $this->createThemeMock('boo', __DIR__ . '/Fixtures/BooThemeBundle/Resources/theme')
        ));
        $finder = new ThemeFinder($manager, new ThemeFilenameParser());
        $references = $finder->findAllTemplates();

        $this->assertCount(6, $references);
        $this->assertContains('foo#BooBundle:Default:index.html.twig', $references);
        $this->assertContains('foo#BooBundle::navigation.html.twig', $references);
        $this->assertContains('foo#::layout.html.twig', $references);
        $this->assertContains('foo#BooBundle::this.is.an.interesting.template.html.twig', $references);
        $this->assertContains('boo#BooBundle::navigation.html.twig', $references);
        $this->assertContains('boo#::this.is.an.interesting.template.html.twig', $references);
    }
} 