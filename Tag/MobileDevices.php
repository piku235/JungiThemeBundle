<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag;

/**
 * MobileDevices tag represents themes designed for mobile only devices (without tablet).
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class MobileDevices extends AbstractMobileDevices
{
    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'jungi.mobile_devices';
    }
}
