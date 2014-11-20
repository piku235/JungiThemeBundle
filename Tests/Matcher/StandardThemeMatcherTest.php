<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Matcher;

use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReference;
use Jungi\Bundle\ThemeBundle\Matcher\StandardThemeMatcher;
use Jungi\Bundle\ThemeBundle\Matcher\VirtualThemeMatcher;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * StandardThemeMatcher Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class StandardThemeMatcherTest extends TestCase
{
    /**
     * @var VirtualThemeMatcher
     */
    protected $matcher;

    /**
     * @var ThemeManager
     */
    protected $manager;

    protected function setUp()
    {
        $desktopTheme = $this->createThemeMock('footheme_desktop');
        $desktopTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\Group('footheme'),
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme = $this->createThemeMock('footheme_mobile');
        $mobileTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\MobileDevices()
            ))));
        $this->manager = new ThemeManager(array(
            $desktopTheme, $mobileTheme
        ));

        $this->matcher = new StandardThemeMatcher($this->manager, new ThemeNameParser());
    }

    public function testValidMatch()
    {
        $request = $this->createDesktopRequest();
        $this->assertSame($this->matcher->match('footheme_desktop', $request), $this->manager->getTheme('footheme_desktop'));
        $this->assertSame($this->matcher->match(new ThemeNameReference('footheme_mobile'), $request), $this->manager->getTheme('footheme_mobile'));
    }

    /**
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException
     */
    public function testOnNonExistentTheme()
    {
        $this->matcher->match('nootheme', $this->createDesktopRequest());
    }

    public function getVirtualThemeMatches()
    {
        return array(
            array('footheme_mobile', 'footheme', $this->createMobileRequest()),
            array('footheme_tablet', 'footheme',  $this->createTabletRequest()),
            array('footheme_desktop', 'footheme', $this->createDesktopRequest()),
        );
    }
}
