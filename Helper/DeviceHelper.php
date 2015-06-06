<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Helper;

use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Symfony\Component\Templating\Helper\Helper;

/**
 * DeviceHelper.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceHelper extends Helper
{
    /**
     * @var MobileDetect
     */
    private $mobileDetect;

    /**
     * Constructor.
     *
     * @param MobileDetect $mobileDetect A mobile detect instance
     */
    public function __construct(MobileDetect $mobileDetect)
    {
        $this->mobileDetect = $mobileDetect;
    }

    /**
     * Checks if the request has been sent by mobile device.
     *
     * @return bool
     */
    public function isMobile()
    {
        return $this->mobileDetect->isMobile();
    }

    /**
     * Checks if the request has been sent by tablet device.
     *
     * @return bool
     */
    public function isTablet()
    {
        return $this->mobileDetect->isTablet();
    }

    /**
     * Checks if the request has been sent by desktop device.
     *
     * @return bool
     */
    public function isDesktop()
    {
        return !$this->mobileDetect->isMobile();
    }

    /**
     * Checks if the request has been sent by a given device.
     *
     * @param string $device A device
     *
     * @return bool
     */
    public function isDevice($device)
    {
        return $this->mobileDetect->is($device);
    }

    /**
     * Returns the name of this helper.
     *
     * @return string
     */
    public function getName()
    {
        return 'jungi_device';
    }
}
