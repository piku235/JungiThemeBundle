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

use Jungi\Bundle\ThemeBundle\Matcher\Filter\DeviceThemeFilter;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\FakeTag;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * DeviceThemeFilter Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceThemeFilterTest extends TestCase
{
    /**
     * @var DeviceThemeFilter
     */
    private $filter;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->filter = new DeviceThemeFilter(new MobileDetect());
    }

    /**
     * Tests the filter on various devices and operating systems
     *
     * @dataProvider getDevicesWithThemes
     */
    public function testOnVariousDevices(array $themeNames, $ua)
    {
        // Themes
        $firstTheme = $this->createThemeMock('footheme_mobile');
        $firstTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\MobileDevices(array('iOS', 'AndroidOS', 'BlackBerryOS', 'WindowsMobileOS', 'WindowsPhoneOS'), Tag\MobileDevices::MOBILE),
            ))));
        $secondTheme = $this->createThemeMock('footheme_androtab');
        $secondTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\MobileDevices(array('AndroidOS'), Tag\MobileDevices::TABLET),
            ))));
        $thirdTheme = $this->createThemeMock('footheme_win');
        $thirdTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\MobileDevices(array('WindowsPhoneOS')),
            ))));
        $fourthTheme = $this->createThemeMock('footheme_global');
        $fourthTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\MobileDevices(),
            ))));
        $desktopTheme = $this->createThemeMock('footheme_desktop');
        $desktopTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\DesktopDevices(),
            ))));

        // Prepare and fire the method
        $collection = new ThemeCollection(array($firstTheme, $secondTheme, $thirdTheme, $fourthTheme, $desktopTheme));
        $this->filter->filter($collection, $this->createRequest($ua));

        // Assert
        $this->assertCount(count($themeNames), $collection);
        foreach ($collection as $theme) {
            $this->assertContains($theme->getName(), $themeNames);
        }
    }

    /**
     * Tests the filter when there're themes without supported tags
     */
    public function testOnNonSupportedThemes()
    {
        // Themes
        $firstTheme = $this->createThemeMock('footheme_first');
        $firstTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection()));
        $secondTheme = $this->createThemeMock('footheme_second');
        $secondTheme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new FakeTag('foo'),
            ))));

        // Prepare and fire the method
        $collection = new ThemeCollection(array($firstTheme, $secondTheme));
        $this->filter->filter($collection, $this->createDesktopRequest());

        // Assert
        $this->assertCount(2, $collection);
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
            array(array('footheme_global', 'footheme_mobile', 'footheme_win'), 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; Acer; Allegro)'),
            array(array('footheme_global', 'footheme_mobile'), 'Mozilla/5.0 (iPod; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25'),
            array(array('footheme_global', 'footheme_mobile'), 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B329 Safari/8536.25'),
            array(array('footheme_global', 'footheme_mobile'), 'Mozilla/5.0 (BlackBerry; U; BlackBerry 9790; en-GB) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.714 Mobile Safari/534.11+'),
            array(array('footheme_global', 'footheme_mobile'), 'Mozilla/5.0 (Linux; U; Android 2.3.7; hd-us; Dell Venue Build/GWK74; CyanogenMod-7.2.0) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'),
            array(array('footheme_global', 'footheme_mobile'), 'Mozilla/5.0 (Linux; Android 4.1.1; HTC One X Build/JRO03C) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19'),
            array(array('footheme_global', 'footheme_mobile'), 'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 920)'),
            // Tablet
            array(array('footheme_global', 'footheme_androtab'), 'Mozilla/5.0 (Linux; Android 4.2.2; TF300T Build/JDQ39E) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166  Safari/535.19'),
            array(array('footheme_global'), 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25'),
            array(array('footheme_global', 'footheme_win'), 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; HTC; iPad 3)'),
            // Other
            array(array('footheme_desktop'), 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Win64; x64; Trident/6.0; Touch; MASMJS)'),
            array(array('footheme_desktop'), 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:25.0) Gecko/20130626 Firefox/25.0'),
        );
    }
}
