<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Templating\Loader;

use Jungi\Bundle\ThemeBundle\Templating\Loader\TemplateLocator;
use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Core\ThemeSource;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference as BaseTemplateReference;

/**
 * TemplateLocator Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateLocatorTest extends TestCase
{
    /**
     * @var ThemeSource
     */
    private $source;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->source = new ThemeSource(array($this->createThemeMock('Foo', '/foo/theme/path')));
    }

    /**
     * Tests on valid theme reference.
     */
    public function testValidThemeReference()
    {
        $template = new TemplateReference(new BaseTemplateReference('bundle', 'controller', 'name', 'format', 'engine'), 'Foo');

        $fileLocator = $this->getFileLocator();
        $fileLocator
            ->expects($this->once())
            ->method('locate')
            ->will($this->returnArgument(0));

        $locator = new TemplateLocator($this->source, $fileLocator);

        $this->assertEquals('/foo/theme/path/bundle/controller/name.format.engine', $locator->locate($template));
    }

    /**
     * Tests when a given theme is not exist.
     *
     * @expectedException \RuntimeException
     */
    public function testWhenThemeIsNotExist()
    {
        $template = new TemplateReference(new BaseTemplateReference('bundle', 'controller', 'name', 'format', 'engine'), 'NonExist');
        $locator = new TemplateLocator($this->source, $this->getFileLocator());

        $locator->locate($template);
    }

    /**
     * Tests on missing theme files
     * Should be used the parent locate().
     */
    public function testMissingThemeFiles()
    {
        $template = new TemplateReference(new BaseTemplateReference('bundle', 'controller', 'name', 'format', 'engine'), 'Foo');

        $fileLocator = $this->getFileLocator();
        $fileLocator
            ->expects($this->exactly(2))
            ->method('locate')
            ->will($this->returnCallback(function ($arg) use ($template) {
                if ($arg == '/foo/theme/path/bundle/controller/name.format.engine') {
                    throw new \InvalidArgumentException('The file was kidnapped, hehe.');
                } elseif ($arg == $template->getOrigin()->getPath()) {
                    return '/path/to/template';
                }
            }));

        $locator = new TemplateLocator($this->source, $fileLocator);

        $this->assertEquals('/path/to/template', $locator->locate($template));
    }

    /**
     * @see \Symfony\Bundle\FrameworkBundle\Tests\Templating\Loader\TemplateLocatorTest::testLocateATemplate()
     */
    public function testLocateATemplate()
    {
        $template = new BaseTemplateReference('bundle', 'controller', 'name', 'format', 'engine');

        $fileLocator = $this->getFileLocator();

        $fileLocator
            ->expects($this->once())
            ->method('locate')
            ->with($template->getPath())
            ->will($this->returnValue('/path/to/template'));

        $locator = new TemplateLocator($this->source, $fileLocator);

        $this->assertEquals('/path/to/template', $locator->locate($template));
    }

    /**
     * @see \Symfony\Bundle\FrameworkBundle\Tests\Templating\Loader\TemplateLocatorTest::testThrowsExceptionWhenTemplateNotFound()
     */
    public function testThrowsExceptionWhenTemplateNotFound()
    {
        $template = new BaseTemplateReference('bundle', 'controller', 'name', 'format', 'engine');

        $fileLocator = $this->getFileLocator();

        $errorMessage = 'FileLocator exception message';

        $fileLocator
            ->expects($this->once())
            ->method('locate')
            ->will($this->throwException(new \InvalidArgumentException($errorMessage)));

        $locator = new TemplateLocator($this->source, $fileLocator);

        try {
            $locator->locate($template);
            $this->fail('->locate() should throw an exception when the file is not found.');
        } catch (\InvalidArgumentException $e) {
            $this->assertContains(
                $errorMessage,
                $e->getMessage(),
                'TemplateLocator exception should propagate the FileLocator exception message'
            );
        }
    }

    /**
     * @see \Symfony\Bundle\FrameworkBundle\Tests\Templating\Loader\TemplateLocatorTest::testThrowsExceptionWhenTemplateNotFound()
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsAnExceptionWhenTemplateIsNotATemplateReferenceInterface()
    {
        $locator = new TemplateLocator($this->source, $this->getFileLocator());
        $locator->locate('template');
    }
}
