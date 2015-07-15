<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Resolver;

use Jungi\Bundle\ThemeBundle\Core\VirtualTheme;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * VirtualThemeTest.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeTest extends TestCase
{
    public function testGoodSettingPointedTheme()
    {
        $childTheme = $this->createThemeMock('bar');
        $theme = new VirtualTheme('foo', array($childTheme));

        // Assert
        $theme->setPointedTheme($childTheme);
        $this->assertEquals($childTheme, $theme->getPointedTheme());
        $theme->setPointedTheme('bar');
        $this->assertEquals($childTheme, $theme->getPointedTheme());
    }

    /**
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException
     */
    public function testSettingAlienPointedTheme()
    {
        $childTheme = $this->createThemeMock('bar');
        $alienTheme = $this->createThemeMock('alien');
        $theme = new VirtualTheme('foo', array($childTheme));

        // Assert
        $theme->setPointedTheme($alienTheme);
    }

    /**
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException
     */
    public function testSettingNonExistingPointedTheme()
    {
        $childTheme = $this->createThemeMock('bar');
        $theme = new VirtualTheme('foo', array($childTheme));

        // Assert
        $theme->setPointedTheme('moo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingInvalidPointedTheme()
    {
        $childTheme = $this->createThemeMock('bar');
        $theme = new VirtualTheme('foo', array($childTheme));

        // Assert
        $theme->setPointedTheme(5);
    }
}
