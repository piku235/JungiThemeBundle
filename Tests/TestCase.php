<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * TestCase
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Creates the simple mobile request
     *
     * @return Request
     */
    protected function createMobileRequest()
    {
        return $this->createRequest('Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25');
    }

    /**
     * Creates the simple tablet request
     *
     * @return Request
     */
    protected function createTabletRequest()
    {
        return $this->createRequest('Mozilla/5.0 (iPad; CPU OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Coast/1.0.2.62956 Mobile/10B329 Safari/7534.48.3');
    }

    /**
     * Creates the simple desktop request
     *
     * @return Request
     */
    protected function createDesktopRequest()
    {
        return $this->createRequest('Mozilla/5.0 (Windows NT 6.2; rv:20.0) Gecko/20100101 Firefox/20.0');
    }

    /**
     * Creates the request
     *
     * @param string $ua A user agent
     *
     * @return Request
     */
    protected function createRequest($ua)
    {
        return new Request(array(), array(), array(), array(), array(),
            array('HTTP_USER_AGENT' => $ua)
        );
    }

    /**
     * Creates the theme mock with a given name
     *
     * @param string $name A theme name
     * @param string $path A theme resource dir (optional)
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createThemeMock($name, $path = null)
    {
        $theme = $this->getMock('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $theme
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path ?: __DIR__));
        $theme
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));

        return $theme;
    }

    /**
     * Returns the Mock of Symfony\Component\Config\FileLocator
     *
     * @return \Symfony\Component\Config\FileLocator
     */
    protected function getFileLocator()
    {
        return $this
            ->getMockBuilder('Symfony\Component\Config\FileLocator')
            ->setMethods(array('locate'))
            ->setConstructorArgs(array('/path/to/fallback'))
            ->getMock();
    }
}
