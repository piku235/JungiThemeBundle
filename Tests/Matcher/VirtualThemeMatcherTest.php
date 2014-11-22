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
use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReference;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\DeviceThemeFilter;
use Jungi\Bundle\ThemeBundle\Matcher\VirtualThemeMatcher;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Matcher\Filter\FakeThemeFilter;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * VirtualThemeMatcher Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeMatcherTest extends TestCase
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
                new Tag\VirtualTheme('footheme'),
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme = $this->createThemeMock('footheme_mobile');
        $mobileTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\MobileDevices(array(), Tag\MobileDevices::MOBILE),
            ))));
        $tabletTheme = $this->createThemeMock('footheme_tablet');
        $tabletTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\MobileDevices(array(), Tag\MobileDevices::TABLET),
            ))));
        $this->manager = new ThemeManager(array(
            $desktopTheme, $mobileTheme, $tabletTheme
        ));

        $this->matcher = new VirtualThemeMatcher($this->manager, new ThemeNameParser(), array(new DeviceThemeFilter(new MobileDetect())));
        $this->matcher->addFilter(new FakeThemeFilter());
    }

    public function testSupports()
    {
        $this->assertTrue($this->matcher->supports('@footheme_desktop'));
        $this->assertTrue($this->matcher->supports(new ThemeNameReference('footheme_mobile', true)));
        $this->assertFalse($this->matcher->supports('footheme_tablet'));
    }

    /**
     * @dataProvider getVirtualThemeMatches
     */
    public function testOnValidMatch($matchTheme, $virtualTheme, $request)
    {
        $reference = new ThemeNameReference($virtualTheme, true);
        $this->assertSame($this->manager->getTheme($matchTheme), $this->matcher->match((string) $reference, $request));
        $this->assertSame($this->manager->getTheme($matchTheme), $this->matcher->match($reference, $request));
    }

    public function testOnExplicitVirtualTheme()
    {
        $barTheme = $this->createThemeMock('bartheme_single');
        $barTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('bartheme'),
            ))));

        $this->manager->addTheme($barTheme);
        $this->assertSame($barTheme, $this->matcher->match('@bartheme', $this->createDesktopRequest()));
    }

    public function testAmbiguous()
    {
        $desktopTheme = $this->createThemeMock('footheme_desktop');
        $desktopTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme1 = $this->createThemeMock('footheme_mobile');
        $mobileTheme1
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\MobileDevices(),
            ))));
        $mobileTheme2 = $this->createThemeMock('footheme_mobile_again');
        $mobileTheme2
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\MobileDevices(),
            ))));

        $reflection = new \ReflectionObject($this->matcher);
        $property = $reflection->getProperty('manager');
        $property->setAccessible(true);
        $property->setValue($this->matcher, new ThemeManager(array(
            $desktopTheme, $mobileTheme1, $mobileTheme2,
        )));

        try {
            $this->matcher->match('@footheme', $this->createMobileRequest());
        } catch (\RuntimeException $e) {
            $this->assertStringStartsWith('There is more than one matching theme', $e->getMessage());

            return;
        }

        $this->fail('Should be thrown an RuntimeException.');
    }

    public function testOnAllFilteredThemes()
    {
        $mobileTheme1 = $this->createThemeMock('footheme_mobile1');
        $mobileTheme1
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme2 = $this->createThemeMock('footheme_mobile2');
        $mobileTheme2
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\DesktopDevices(),
            ))));
        $mobileTheme3 = $this->createThemeMock('footheme_mobile3');
        $mobileTheme3
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\VirtualTheme('footheme'),
                new Tag\DesktopDevices(),
            ))));

        $reflection = new \ReflectionObject($this->matcher);
        $property = $reflection->getProperty('manager');
        $property->setAccessible(true);
        $property->setValue($this->matcher, new ThemeManager(array(
            $mobileTheme1, $mobileTheme2, $mobileTheme3,
        )));

        try {
            $this->matcher->match('@footheme', $this->createMobileRequest());
        } catch (\RuntimeException $e) {
            $this->assertStringStartsWith('There is no matching theme', $e->getMessage());

            return;
        }

        $this->fail('Should be thrown an RuntimeException.');
    }

    /**
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException
     */
    public function testOnNonExistentVirtualTheme()
    {
        $this->matcher->match('@mootheme', $this->createDesktopRequest());
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
