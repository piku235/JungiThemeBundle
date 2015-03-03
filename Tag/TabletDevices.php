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
 * TabletDevices represents themes that are suitable for tablet only devices
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TabletDevices extends AbstractMobileDevices
{
    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'jungi.tablet_devices';
    }
}
