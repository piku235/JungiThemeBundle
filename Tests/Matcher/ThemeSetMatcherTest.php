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

use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\DeviceThemeFilter;
use Jungi\Bundle\ThemeBundle\Matcher\ThemeSetMatcher;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Matcher\Filter\FakeThemeFilter;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tag;

/**
 * ThemeSetMatcherTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeSetMatcherTest extends TestCase
{
    /**
     * @var ThemeSetMatcher
     */
    private $matcher;

    protected function setUp()
    {
        $this->matcher = new ThemeSetMatcher(array(new DeviceThemeFilter(new MobileDetect())));
        $this->matcher->addFilter(new FakeThemeFilter());
    }

    public function testOnExplicitVirtualTheme()
    {
        $barTheme = $this->createThemeMock('bartheme_single');
        $barTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\DesktopDevices(),
            ))));

        $this->assertSame($barTheme, $this->matcher->match(array($barTheme), $this->createDesktopRequest()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOnEmptySet()
    {
        $this->matcher->match(array(), $this->createDesktopRequest());
    }

    public function testOnValidMatch()
    {
        $desktopTheme = $this->createThemeMock('footheme_desktop');
        $desktopTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme = $this->createThemeMock('footheme_mobile');
        $mobileTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\MobileDevices(array(), Tag\MobileDevices::MOBILE),
            ))));
        $tabletTheme = $this->createThemeMock('footheme_tablet');
        $tabletTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\MobileDevices(array(), Tag\MobileDevices::TABLET),
            ))));
        $themes = array($desktopTheme, $mobileTheme, $tabletTheme);

        foreach ($this->getVirtualThemeMatches() as $args) {
            list($matchTheme, $request) = $args;
            $this->assertEquals($matchTheme, $this->matcher->match($themes, $request)->getName());
        }
    }

    public function testAmbiguous()
    {
        $desktopTheme = $this->createThemeMock('footheme_desktop');
        $desktopTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme1 = $this->createThemeMock('footheme_mobile');
        $mobileTheme1
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\MobileDevices(),
            ))));
        $mobileTheme2 = $this->createThemeMock('footheme_mobile2');
        $mobileTheme2
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\MobileDevices(),
            ))));
        $themes = array($desktopTheme, $mobileTheme1, $mobileTheme2);

        try {
            $this->matcher->match($themes, $this->createMobileRequest());
        } catch (\RuntimeException $e) {
            $this->assertStringStartsWith('There is more than one matching theme', $e->getMessage());

            return;
        }

        $this->fail('Should be thrown an RuntimeException.');
    }

    public function testOnAllFilteredThemes()
    {
        $desktop1 = $this->createThemeMock('footheme_desktop1');
        $desktop1
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\DesktopDevices(),
            ))));
        $desktop2 = $this->createThemeMock('footheme_desktop2');
        $desktop2
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\DesktopDevices(),
            ))));
        $desktop3 = $this->createThemeMock('footheme_desktop3');
        $desktop3
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new Tag\TagCollection(array(
                new Tag\DesktopDevices(),
            ))));
        $themes = array($desktop1, $desktop2, $desktop3);

        try {
            $this->matcher->match($themes, $this->createMobileRequest());
        } catch (\RuntimeException $e) {
            $this->assertStringStartsWith('There is no matching theme', $e->getMessage());

            return;
        }

        $this->fail('Should be thrown an RuntimeException.');
    }

    public function getVirtualThemeMatches()
    {
        return array(
            array('footheme_mobile', $this->createMobileRequest()),
            array('footheme_tablet',  $this->createTabletRequest()),
            array('footheme_desktop', $this->createDesktopRequest()),
        );
    }
}
