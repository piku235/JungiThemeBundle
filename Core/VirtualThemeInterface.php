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
 * of them will be used by virtual theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface VirtualThemeInterface extends ThemeInterface
{
    /**
     * Sets a theme which will be used by the virtual theme.
     *
     * @param string|ThemeInterface $pointed A theme name or a theme instance
     *
     * @throws \InvalidArgumentException If the passed theme has a wrong type
     * @throws ThemeNotFoundException    If the given theme does not belongs to the virtual theme
     */
    public function setPointedTheme($pointed);

    /**
     * Returns the parent theme.
     *
     * @return ThemeInterface
     */
    public function getPointedTheme();

    /**
     * Returns the child themes of the virtual theme.
     *
     * @return ThemeCollection
     */
    public function getThemes();
}
