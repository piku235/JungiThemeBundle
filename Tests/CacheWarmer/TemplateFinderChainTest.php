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

use Jungi\Bundle\ThemeBundle\CacheWarmer\TemplateFinderChain;
use Jungi\Bundle\ThemeBundle\CacheWarmer\ThemeFinder;
use Jungi\Bundle\ThemeBundle\Core\ThemeFilenameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Tests\CacheWarmer\Fixtures\OrdinaryBundle\OrdinaryBundle;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinder;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateFilenameParser;

/**
 * TemplateFinderChain Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateFinderChainTest extends TestCase
{
    public function testFind()
    {
        $manager = new ThemeManager(array(
            $this->createThemeMock('foo', __DIR__ . '/Fixtures/FooThemeBundle/Resources/theme'),
            $this->createThemeMock('boo', __DIR__ . '/Fixtures/BooThemeBundle/Resources/theme')
        ));
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel
            ->expects($this->any())
            ->method('getBundles')
            ->will($this->returnValue(array('OrdinaryBundle' => new OrdinaryBundle())))
        ;
        $chain = new TemplateFinderChain(array(new ThemeFinder($manager, new ThemeFilenameParser())));
        $chain->addFinder(new TemplateFinder($kernel, new TemplateFilenameParser(), __DIR__ . '/Fixtures/Resources'));
        $references = $chain->findAllTemplates();

        $this->assertCount(9, $references);
        $this->assertContains('foo#BooBundle:Default:index.html.twig', $references);
        $this->assertContains('boo#::this.is.an.interesting.template.html.twig', $references);
        $this->assertContains('OrdinaryBundle:Default:index.html.twig', $references);
        $this->assertContains('OrdinaryBundle::layout.html.twig', $references);
        $this->assertContains('::layout.html.twig', $references);
    }
} 