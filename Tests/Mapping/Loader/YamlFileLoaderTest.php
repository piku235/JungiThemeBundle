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

use Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlDefinitionLoader;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Fake as FakeTag;

/**
 * YamlFileLoader Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class YamlFileLoaderTest extends DefinitionLoaderTest
{
    /**
     * @var YamlDefinitionLoader
     */
    private $loader;

    /**
     * Set up.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loader = new YamlDefinitionLoader(
            $this->processor,
            $this->registry,
            $this->createFileLocator(__DIR__.'/Fixtures/yml')
        );
    }

    public function testConstantParsing()
    {
        $this->loadFile('constant');

        $theme = $this->registry->getThemeDefinition('foo_1');
        $tags = $theme->getTags();
        $this->assertEquals(array('something_with_const@foo_word'), $tags[0]->getArguments());
        $this->assertEquals(array(FakeTag::SPECIAL), $tags[1]->getArguments());
    }

    public function testSupports()
    {
        $this->assertTrue($this->loader->supports('foo.yml'));
        $this->assertTrue($this->loader->supports('foo.another', 'yml'));
        $this->assertFalse($this->loader->supports('foo.php'));
    }

    public function testEmpty()
    {
        try {
            $this->loadFile('empty');
        } catch (\Exception $e) {
            $this->fail('Empty theme mapping files should be omitted.');
        }
    }

    public function testInvalidInfoType()
    {
        try {
            $this->loadFile('invalid_info_type');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('must be an array', $e->getMessage());
        }
    }

    public function testInvalidTagsType()
    {
        try {
            $this->loadFile('invalid_tags_type');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('must be an array', $e->getMessage());
        }
    }

    public function testMissingThemesNode()
    {
        try {
            $this->loadFile('missing_themes_node');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('There is missing "themes" node', $e->getMessage());
        }
    }

    public function testVirtualThemeWithInvalidThemes()
    {
        try {
            $this->loadFile('invalid_virtual_theme_references');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('There is missing key "theme" for a theme reference', $e->getMessage());
        }
    }

    public function testVirtualThemeWithInvalidThemesKey()
    {
        try {
            $this->loadFile('invalid_virtual_themes_key');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('must be an array', $e->getMessage());
        }
    }

    public function testVirtualThemeWithMissingThemes()
    {
        try {
            $this->loadFile('virtual_missing_themes');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('The "themes" key is missing', $e->getMessage());
        }
    }

    public function testInvalidParametersType()
    {
        try {
            $this->loadFile('invalid_parameters_type');

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('The "parameters" key should must be an array', $e->getMessage());
        }
    }

    public function testOnMissingPath()
    {
        try {
            $this->loadFile('missing_path');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('The "path" key is missing', $e->getMessage());
        }
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidContent()
    {
        $this->loadFile('invalid_content');
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
