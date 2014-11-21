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

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * Theme Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeTest extends TestCase
{
    /**
     * @dataProvider getValidThemeNames
     */
    public function testValidCreation($name)
    {
        $info = $this->getMock('Jungi\Bundle\ThemeBundle\Information\ThemeInfo');
        $path = 'path/to/theme';
        $tags = new TagCollection();
        $theme = new Theme($name, $path, $info, $tags);

        $this->assertEquals($name, $theme->getName());
        $this->assertEquals($path, $theme->getPath());
        $this->assertSame($info, $theme->getInformation());
        $this->assertSame($tags, $theme->getTags());
    }

    /**
     * @dataProvider getInvalidThemeNames
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreation($name)
    {
        $info = $this->getMock('Jungi\Bundle\ThemeBundle\Information\ThemeInfo');
        $theme = new Theme($name, 'path/to/theme', $info, new TagCollection());
    }

    public function getInvalidThemeNames()
    {
        return array(
            array('.footheme'),
            array('@footheme'),
            array('-bar'),
            array('foo123bar[]'),
            array('aBr-theme'),
            array('bootheme edition'),
        );
    }

    public function getValidThemeNames()
    {
        return array(
            array('footheme'),
            array('g12345'),
            array('foo123bar'),
            array('bar-theme'),
            array('bootheme_edition'),
            array('footheme.mobile'),
        );
    }
}
