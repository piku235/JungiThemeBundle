<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Selector\EventListener;

use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Selector\EventListener\DeviceThemeSwitch;
use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeManager;

/**
 * DeviceThemeSwitch Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceThemeSwitchTest extends TestCase
{
    /**
     * @var DeviceThemeSwitch
     */
    protected $switch;

    /**
     * @var ThemeManager
     */
    protected $manager;

    /**
     * @var ThemeResolverInterface
     */
    protected $resolver;

    /**
     * (non-PHPdoc)
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $desktopTheme = $this->createThemeMock('footheme');
        $desktopTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\DesktopDevices()
            ))));

        $this->manager = new ThemeManager(array($desktopTheme));
        $this->switch = new DeviceThemeSwitch(new MobileDetect());
        $this->resolver = $this->getMock('Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface');
    }

    /**
     * (non-PHPdoc)
     *
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->switch = null;
        $this->manager = null;
        $this->resolver = null;
    }

    /**
     * Tests theme switch on different devices
     *
     * @dataProvider getDevicesWithThemes
     */
    public function testOnDifferentDevices($themeName, $ua)
    {
        // Themes
        $firstTheme = $this->createThemeMock('footheme_mobile');
        $firstTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\Link('footheme'),
                new Tag\MobileDevices(array('iOS', 'AndroidOS', 'BlackBerryOS', 'WindowsMobileOS'), Tag\MobileDevices::MOBILE)
            ))));
        $secondTheme = $this->createThemeMock('footheme_tablet');
        $secondTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\Link('footheme'),
                new Tag\MobileDevices(array('AndroidOS'), Tag\MobileDevices::TABLET)
            ))));
        $thirdTheme = $this->createThemeMock('footheme_win');
        $thirdTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\Link('footheme'),
                new Tag\MobileDevices(array('WindowsPhoneOS'))
            ))));
        $this->manager->addTheme($firstTheme);
        $this->manager->addTheme($secondTheme);
        $this->manager->addTheme($thirdTheme);

        // Prepare and fire the method
        $request = $this->createRequest($ua);
        $event = new ResolvedThemeEvent($this->manager->getTheme('footheme'), $this->manager, $this->resolver, $request);
        $this->switch->onResolvedTheme($event);

        // Assert
        $this->assertEquals($themeName, $event->getTheme()->getName());
    }

    /**
     * Tests theme switch on missing required Link tag
     */
    public function testOnMissingRequiredTag()
    {
        $simpleTheme = $this->createThemeMock('footheme_mobile');
        $simpleTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\MobileDevices(array('iOS', 'AndroidOS'))
            ))));
        $this->manager->addTheme($simpleTheme);

        // Prepare and fire the method
        $request = $this->createMobileRequest();
        $event = new ResolvedThemeEvent($this->manager->getTheme('footheme'), $this->manager, $this->resolver, $request);
        $this->switch->onResolvedTheme($event);

        // Assert
        $this->assertNotEquals('footheme_mobile', $event->getTheme()->getName());
    }

    /**
     * Tests theme switch on self sufficient theme
     */
    public function testOnSelfSufficientTheme()
    {
        $simpleTheme = $this->createThemeMock('footheme_boo');
        $simpleTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\DesktopDevices(),
                new Tag\MobileDevices(array('iOS', 'AndroidOS'))
            ))));
        $this->manager->addTheme($simpleTheme);

        // Prepare and fire the method
        $request = $this->createMobileRequest();
        $event = new ResolvedThemeEvent($this->manager->getTheme('footheme_boo'), $this->manager, $this->resolver, $request);
        $this->switch->onResolvedTheme($event);

        // Assert
        $this->assertEquals('footheme_boo', $event->getTheme()->getName());
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getDevicesWithThemes()
    {
        return array(
            // Mobile
            array('footheme_win', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; Acer; Allegro)'),
            array('footheme_mobile', 'Mozilla/5.0 (Linux; U; Android 2.3.7; en-in; MB525 Build/GWK74; CyanogenMod-7.2.0) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'),
            array('footheme_mobile', 'Mozilla/5.0 (iPod; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25'),
            array('footheme_mobile', 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B329 Safari/8536.25'),
            array('footheme_mobile', 'Mozilla/5.0 (BlackBerry; U; BlackBerry 9790; en-GB) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.714 Mobile Safari/534.11+'),
            array('footheme_mobile', 'Mozilla/5.0 (Linux; U; Android 2.3.7; hd-us; Dell Venue Build/GWK74; CyanogenMod-7.2.0) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'),
            array('footheme_win', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; HTC; Titan)'),
            array('footheme_mobile', 'Mozilla/5.0 (Linux; Android 4.1.1; HTC One X Build/JRO03C) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19'),
            array('footheme_mobile', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 920)'),
            array('footheme_mobile', 'Mozilla/5.0 (Linux; U; Android 4.1.2; it-it; Galaxy Nexus Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'),
            // Tablet
            array('footheme_tablet', 'Mozilla/5.0 (Linux; Android 4.1.1; A701 Build/JRO03H) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166  Safari/535.19'),
            array('footheme_tablet', 'Mozilla/5.0 (Linux; Android 4.2.2; TF300T Build/JDQ39E) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166  Safari/535.19'),
            array('footheme_tablet', 'Mozilla/5.0 (Linux; U; Android 4.0.3; en-us; KFOTE Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30'),
            array('footheme_tablet', 'Mozilla/5.0 (Linux; Android 4.1.1; Transformer Build/JRO03L) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Safari/535.19'),
            array('footheme_tablet', 'Mozilla/5.0 (Linux; Android 4.3; Nexus 10 Build/JWR66Y) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.72 Safari/537.36'),
            array('footheme_win', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; HTC; iPad 3)'),
            // Other
            array('footheme', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Win64; x64; Trident/6.0; Touch; MASMJS)'),
            array('footheme', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:25.0) Gecko/20130626 Firefox/25.0'),
            array('footheme', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0')
        );
    }
}
