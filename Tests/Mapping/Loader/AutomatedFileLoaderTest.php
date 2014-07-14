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

use Jungi\Bundle\ThemeBundle\Core\Details;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Tag\Core\TagCollection;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Own;
use Jungi\Bundle\ThemeBundle\Tag;


/**
 * AbstractFileLoader Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AutomatedFileLoaderTest extends AbstractFileLoaderTest
{
    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        if (!defined('CONST_TEST')) {
            define('CONST_TEST', 'testing');
        }
    }

    /**
     * Tests on valid theme mapping
     */
    public function testOnValidThemeMapping()
    {
        $this->loadFile('correct_themes');

        $details = new Details(array(
            'name' => 'A fancy theme',
            'version' => '1.0.0',
            'description' => '<i>foo desc</i>',
            'license' => 'MIT',
            'author.name' => 'piku235',
            'author.email' => 'piku235@gmail.com',
            'author.site' => 'http://test.pl'
        ));
        $themes = array(
            new Theme('foo_1', __DIR__ . '/Fixtures/fake_bundle', $details, new TagCollection(array(
                new Tag\DesktopDevices(),
                new Tag\MobileDevices(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE),
                new Own('test')
            ))),
            new Theme('foo_2', __DIR__ . '/Fixtures/fake_bundle', $details, new TagCollection(array(
                new Own(Own::SPECIAL)
            ))),
            new Theme('foo_3', __DIR__ . '/Fixtures/fake_bundle', $details, new TagCollection(array(
                new Own(CONST_TEST)
            ))),
            new Theme('foo_4', __DIR__ . '/Fixtures/fake_bundle', new Details(array(
                'name' => 'A fancy theme',
                'version' => '1.0.0'
            )))
        );

        foreach ($themes as $theme) {
            $this->assertEquals($theme, $this->manager->getTheme($theme->getName()));
        }
    }

    /**
     * Loads the given file
     *
     * @param string $file A file without ext
     *
     * @return void
     */
    abstract protected function loadFile($file);
}