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

use Jungi\Bundle\ThemeBundle\Mapping\Loader\LoaderHelper;
use Jungi\Bundle\ThemeBundle\Mapping\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * XmlFileLoader Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class XmlFileLoaderTest extends AutomatedFileLoaderTest
{
    /**
     * @var XmlFileLoader
     */
    private $loader;

    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loader = new XmlFileLoader(
            $this->registry,
            new FileLocator($this->kernel, __DIR__.'/Fixtures/xml'),
            $this->tagFactory,
            new LoaderHelper($this->tagRegistry)
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testLoad()
    {
        $this->loader->load('../yml/full.yml');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalid()
    {
        $this->loadFile('invalid');
    }

    /**
     * Invalid theme mappings
     *
     * @return array
     */
    public function getInvalidThemeMappings()
    {
        $result = parent::getInvalidThemeMappings();
        $result[] = array('invalid_info_missing_key');

        return $result;
    }

    /**
     * Loads the given file
     *
     * @param string $file A file without ext
     *
     * @return void
     */
    protected function loadFile($file)
    {
        $this->loader->load($file.'.xml');
    }
}
