<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Templating;

use Jungi\Bundle\ThemeBundle\Core\ThemeHolder;
use Jungi\Bundle\ThemeBundle\Templating\TemplateNameParser;
use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference as BaseTemplateReference;
use Symfony\Component\Templating\TemplateReference as MotherTemplateReference;

/**
 * TemplateNameParserTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateNameParserTest extends TestCase
{
    /**
     * @var TemplateNameParser
     */
    private $parser;

    /**
     * @var ThemeHolder
     */
    private $holder;

    /**
     * Sets up the environment
     *
     * @return void
     */
    protected function setUp()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel
            ->expects($this->any())
            ->method('getBundle')
            ->will($this->returnCallback(function ($bundle) {
                if (in_array($bundle, array('JungiTestBundle', 'SensioFooBundle', 'SensioCmsFooBundle', 'FooBundle'))) {
                    return true;
                }

                throw new \InvalidArgumentException();
            }));

        $this->holder = new ThemeHolder();
        $this->holder->setTheme($this->createThemeMock('Foo'));
        $this->parser = new TemplateNameParser($this->holder, $kernel);
    }

    /**
     * Tests the parse method with valid examples
     *
     * @dataProvider getValidLogicalNames
     */
    public function testOnValidName($name, $ref)
    {
        $template = $this->parser->parse($name);

        $this->assertEquals($template->getLogicalName(), $ref->getLogicalName());
    }

    /**
     * Tests on an empty theme
     */
    public function testOnEmptyTheme()
    {
        $ref = new \ReflectionObject($this->holder);
        $prop = $ref->getProperty('theme');
        $prop->setAccessible(true);
        $prop->setValue($this->holder, null);

        $template = $this->parser->parse('FooBundle:Default:index.html.twig');

        $this->assertEquals($template, new BaseTemplateReference('FooBundle', 'Default', 'index', 'html', 'twig'));
    }

    /**
     * The data provider
     *
     * @return array
     */
    public function getValidLogicalNames()
    {
        return array(
            array('FooBundle:Default:index.html.twig', new TemplateReference(new BaseTemplateReference('FooBundle', 'Default', 'index', 'html', 'twig'), 'Foo')),
            array('JungiTestBundle::index.html.twig', new TemplateReference(new BaseTemplateReference('JungiTestBundle', null, 'index', 'html', 'twig'), 'Foo')),
            array('::index.html.twig', new TemplateReference(new BaseTemplateReference(null, null, 'index', 'html', 'twig'), 'Foo')),
            array(':FooBundle:index.html.twig', new TemplateReference(new BaseTemplateReference(null, 'FooBundle', 'index', 'html', 'twig'), 'Foo')),
            array('/path/to/section/name.php', new MotherTemplateReference('/path/to/section/name.php', 'php')),
            array('name.twig', new MotherTemplateReference('name.twig', 'twig')),
            array('name', new MotherTemplateReference('name')),
        );
    }
}
