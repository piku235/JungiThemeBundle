<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Core;

use Jungi\Bundle\ThemeBundle\Tests\Fixtures\FakeThemeHolder;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Jungi\Bundle\ThemeBundle\Core\ThemeReference;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParser;

/**
 * ThemeNameParserTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeNameParserTest extends TestCase
{
    /**
     * @var ThemeNameParser
     */
    private $parser;

    /**
     * @var FakeThemeHolder
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

        $this->holder = new FakeThemeHolder();
        $this->holder->setTheme($this->createThemeMock('Foo'));
        $this->parser = new ThemeNameParser($this->holder, $kernel);
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->parser = null;
        $this->holder = null;
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
     * @dataProvider      getInvalidLogicalNames
     * @expectedException \InvalidArgumentException
     */
    public function testOnInvalidName($name)
    {
        $this->parser->parse($name);
    }

    /**
     * Tests on an empty theme
     */
    public function testOnEmptyTheme()
    {
        $this->holder->theme = null;
        $template = $this->parser->parse('FooBundle:Default:index.html.twig');

        $this->assertEquals($template, new TemplateReference('FooBundle', 'Default', 'index', 'html', 'twig'));
    }

    /**
     * The data provider
     *
     * @return array
     */
    public function getInvalidLogicalNames()
    {
        return array(
            array('BarBundle:Post:index.html.php'),
            array('FooBundle:Post:index'),
            array('FooBundle:Post'),
            array('FooBundle:Post:foo:bar'),
        );
    }

    /**
     * The data provider
     *
     * @return array
     */
    public function getValidLogicalNames()
    {
        return array(
            array('FooBundle:Default:index.html.twig', new ThemeReference(new TemplateReference('FooBundle', 'Default', 'index', 'html', 'twig'), 'Foo')),
            array('JungiTestBundle::index.html.twig', new ThemeReference(new TemplateReference('JungiTestBundle', null, 'index', 'html', 'twig'), 'Foo')),
            array('::index.html.twig', new ThemeReference(new TemplateReference(null, null, 'index', 'html', 'twig'), 'Foo')),
            array(':FooBundle:index.html.twig', new ThemeReference(new TemplateReference(null, 'FooBundle', 'index', 'html', 'twig'), 'Foo'))
        );
    }
}
