<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Twig\Extension;

use Jungi\Bundle\ThemeBundle\Helper\DeviceHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DeviceExtension.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A symfony container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_mobile', array($this, 'isMobile')),
            new \Twig_SimpleFunction('is_tablet', array($this, 'isTablet')),
            new \Twig_SimpleFunction('is_desktop', array($this, 'isDesktop')),
            new \Twig_SimpleFunction('is_device', array($this, 'isDevice')),
        );
    }

    /**
     * Checks if the request has been sent by mobile device.
     *
     * @return boolean
     */
    public function isMobile()
    {
        return $this->getDeviceHelper()->isMobile();
    }

    /**
     * Checks if the request has been sent by tablet device.
     *
     * @return boolean
     */
    public function isTablet()
    {
        return $this->getDeviceHelper()->isTablet();
    }

    /**
     * Checks if the request has been sent by desktop device.
     *
     * @return boolean
     */
    public function isDesktop()
    {
        return $this->getDeviceHelper()->isDesktop();
    }

    /**
     * Checks if the request has been sent by a given device.
     *
     * @param string $device A device
     *
     * @return boolean
     */
    public function isDevice($device)
    {
        return $this->getDeviceHelper()->isDevice($device);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'jungi_device';
    }

    /**
     * Returns the mobile detect instance.
     *
     * @return DeviceHelper
     */
    private function getDeviceHelper()
    {
        return $this->container->get('jungi_theme.helper.device');
    }
}
