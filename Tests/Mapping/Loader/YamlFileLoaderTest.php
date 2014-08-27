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
use Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * YamlFileLoader Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class YamlFileLoaderTest extends AutomatedFileLoaderTest
{
    /**
     * @var YamlFileLoader
     */
    private $loader;

    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loader = new YamlFileLoader(
            $this->manager,
            new FileLocator($this->kernel, __DIR__ . '/Fixtures/yml'),
            $this->tagFactory,
            new LoaderHelper($this->tagRegistry)
        );
    }

    /**
     * Tear down
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->loader = null;
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
        $this->loader->load($file . '.yml');
    }
}
