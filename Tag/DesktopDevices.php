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
 * DesktopDevices identifies themes designed for desktop devices.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DesktopDevices implements TagInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEqual(TagInterface $tag)
    {
        return $tag instanceof static;
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'jungi.desktop_devices';
    }
}
