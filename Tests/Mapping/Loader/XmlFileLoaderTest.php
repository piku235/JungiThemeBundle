<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Loader;

use Jungi\Bundle\ThemeBundle\Mapping\Loader\XmlDefinitionLoader;

/**
 * XmlFileLoader Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class XmlFileLoaderTest extends DefinitionLoaderTest
{
    /**
     * @var XmlDefinitionLoader
     */
    private $loader;

    /**
     * Set up.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loader = new XmlDefinitionLoader(
            $this->processor,
            $this->registry,
            $this->createFileLocator(__DIR__.'/Fixtures/xml')
        );
    }

    public function testSupports()
    {
        $this->assertTrue($this->loader->supports('foo.xml'));
        $this->assertTrue($this->loader->supports('foo.another', 'xml'));
        $this->assertFalse($this->loader->supports('foo.yml'));
    }

    public function testInvalidFile()
    {
        try {
            $this->loadFile('invalid');

            $this->fail('RuntimeException with the should be thrown.');
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf('\InvalidArgumentException', $e->getPrevious());
        }
    }

    public function testOnMissingFile()
    {
        try {
            $this->loadFile('missing');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('The file "missing.xml" does not exist', $e->getMessage());
        }
    }

    /**
     * Loads the given file.
     *
     * @param string $file A file without ext
     */
    protected function loadFile($file)
    {
        $this->loader->load($file.'.xml');
    }
}
