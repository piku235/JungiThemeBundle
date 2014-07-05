<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Helper;

use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Jungi\Bundle\ThemeBundle\Helper\DeviceHelper;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * DeviceHelperTest Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceHelperTest extends TestCase
{
    /**
     * Tests the isMobile
     */
    public function testMobile()
    {
        $requestStack = new RequestStack();
        $requestStack->push($this->createMobileRequest());
        $extension = new DeviceHelper(new MobileDetect($requestStack));

        $this->assertTrue($extension->isMobile());
        $this->assertFalse($extension->isDesktop());
        $this->assertFalse($extension->isTablet());
    }

    /**
     * Tests the isTablet
     */
    public function testTablet()
    {
        $requestStack = new RequestStack();
        $requestStack->push($this->createTabletRequest());
        $extension = new DeviceHelper(new MobileDetect($requestStack));

        $this->assertTrue($extension->isTablet());
        $this->assertTrue($extension->isMobile());
        $this->assertFalse($extension->isDesktop());
    }

    /**
     * Tests the isDesktop
     */
    public function testDesktop()
    {
        $requestStack = new RequestStack();
        $requestStack->push($this->createDesktopRequest());
        $extension = new DeviceHelper(new MobileDetect($requestStack));

        $this->assertTrue($extension->isDesktop());
        $this->assertFalse($extension->isTablet());
        $this->assertFalse($extension->isMobile());
    }

    /**
     * Tests the isDevice
     */
    public function testDevice()
    {
        $requestStack = new RequestStack();
        $requestStack->push($this->createMobileRequest());
        $extension = new DeviceHelper(new MobileDetect($requestStack));

        $this->assertTrue($extension->isDevice('iOS'));
        $this->assertTrue($extension->isDevice('Safari'));
        $this->assertTrue($extension->isDevice('iPhone'));
    }
}
