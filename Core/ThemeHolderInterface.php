<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

/**
 * Classes which implements this interface are used only for holding the current theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeHolderInterface
{
    /**
     * Returns the current theme.
     *
     * @return ThemeInterface|null Null if the theme was not set
     */
    public function getTheme();

    /**
     * Sets the current theme.
     *
     * @param ThemeInterface $theme A theme
     */
    public function setTheme(ThemeInterface $theme);
}
