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
 * The virtual theme is basically a container of standard themes where
 * only one of them will be used
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface VirtualThemeInterface extends ThemeInterface
{
    /**
     * Sets a theme which will be used by the virtual theme
     *
     * @param ThemeInterface $parent A theme
     *
     * @return void
     */
    public function setDecoratedTheme(ThemeInterface $parent);

    /**
     * Returns the parent theme
     *
     * @return ThemeInterface
     */
    public function getDecoratedTheme();

    /**
     * Returns the themes that belongs to the virtual theme
     *
     * @return ThemeInterface[]
     */
    public function getThemes();
}
