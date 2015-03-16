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

use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;

/**
 * A virtual theme is basically a container of standard themes where only one
 * of them will be used.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface VirtualThemeInterface extends ThemeInterface
{
    /**
     * Sets a theme which will be used by the virtual theme.
     *
     * @param ThemeInterface $pointed A theme
     *
     * @throws ThemeNotFoundException If the given theme not belongs to the virtual theme
     */
    public function setPointedTheme(ThemeInterface $pointed);

    /**
     * Returns the parent theme.
     *
     * @return ThemeInterface
     */
    public function getPointedTheme();

    /**
     * Returns the local theme registry of the virtual theme.
     *
     * @return ThemeCollection
     */
    public function getThemes();
}