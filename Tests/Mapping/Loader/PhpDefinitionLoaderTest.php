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

use Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpDefinitionLoader;

/**
 * PhpDefinitionLoader Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpDefinitionLoaderTest extends DefinitionLoaderTest
{
    /**
     * @var PhpDefinitionLoader
     */
    private $loader;

    /**
     * Set up.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loader = new PhpDefinitionLoader(
            $this->processor,
            $this->registry,
            $this->createFileLocator(__DIR__.'/Fixtures/php')
        );
    }

    public function testSupports()
    {
        $this->assertTrue($this->loader->supports('foo.php'));
        $this->assertTrue($this->loader->supports('foo.another', 'php'));
        $this->assertFalse($this->loader->supports('foo.yml'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidRegistry()
    {
        $this->loadFile('invalid_registry');
    }

    /**
     * Loads the given file.
     *
     * @param string $file A file without ext
     */
    protected function loadFile($file)
    {
        $this->loader->load($file.'.php');
    }
}
