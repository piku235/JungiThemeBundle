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

use Jungi\Bundle\ThemeBundle\CacheWarmer\TemplateFinder;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistry;
use Jungi\Bundle\ThemeBundle\Core\VirtualTheme;
use Jungi\Bundle\ThemeBundle\Templating\TemplateFilenameParser;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * TemplateFinder Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateFinderTest extends TestCase
{
    public function testFind()
    {
        $virtualTheme = new VirtualTheme('virtual', array(
            $this->createThemeMock('foo', __DIR__.'/Fixtures/VirtualThemeBundle/Resources/theme/mobile'),
            $this->createThemeMock('boo', __DIR__.'/Fixtures/VirtualThemeBundle/Resources/theme/desktop'),
        ));
        $registry = new ThemeRegistry(array(
            $this->createThemeMock('foo', __DIR__.'/Fixtures/FooThemeBundle/Resources/theme'),
            $this->createThemeMock('boo', __DIR__.'/Fixtures/BooThemeBundle/Resources/theme'),
            $virtualTheme,
        ));
        $finder = new TemplateFinder($registry, new TemplateFilenameParser());
        $references = $finder->findAllTemplates();

        $this->assertCount(12, $references);
        $this->assertContains('foo#BooBundle:Default:index.html.twig', $references);
        $this->assertContains('foo#BooBundle::navigation.html.twig', $references);
        $this->assertContains('foo#BooBundle::this.is.an.interesting.template.html.twig', $references);
        $this->assertContains('foo#::layout.html.twig', $references);
        $this->assertContains('boo#BooBundle::navigation.html.twig', $references);
        $this->assertContains('boo#::this.is.an.interesting.template.html.twig', $references);
        $this->assertContains('virtual.foo#::layout.html.twig', $references);
        $this->assertContains('virtual.foo#BooBundle::navigation.html.twig', $references);
        $this->assertContains('virtual.foo#BooBundle::this.is.an.interesting.template.html.twig', $references);
        $this->assertContains('virtual.foo#BooBundle:Default:index.html.twig', $references);
        $this->assertContains('virtual.boo#::layout.html.twig', $references);
        $this->assertContains('virtual.boo#BooBundle::navigation.html.twig', $references);
    }
}
