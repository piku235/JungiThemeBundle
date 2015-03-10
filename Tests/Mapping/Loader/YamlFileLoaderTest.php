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
 * YamlFileLoader Test Case.
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
     * Set up.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loader = new YamlFileLoader(
            $this->registry,
            new FileLocator($this->kernel, __DIR__.'/Fixtures/yml'),
            $this->tagFactory,
            new LoaderHelper($this->tagRegistry)
        );
    }

    public function testEmpty()
    {
        try {
            $this->loadFile('empty');
        } catch (\Exception $e) {
            $this->fail('Empty theme mapping files should be omitted.');
        }
    }

    /**
     * @expectedException \DomainException
     */
    public function testLoad()
    {
        $this->loader->load('../xml/full.xml');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidContent()
    {
        $this->loadFile('invalid_content');
    }

    /**
     * Invalid theme mappings.
     *
     * @return array
     */
    public function getInvalidThemeMappings()
    {
        $result = parent::getInvalidThemeMappings();
        $result[] = array('invalid_themes_definition');
        $result[] = array('missing_info');
        $result[] = array('missing_path');
        $result[] = array('unrecognized_theme_keys');

        return $result;
    }

    /**
     * Loads the given file.
     *
     * @param string $file A file without ext
     */
    protected function loadFile($file)
    {
        $this->loader->load($file.'.yml');
    }
}
