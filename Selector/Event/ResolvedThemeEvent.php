<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Selector\Event;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Event\ThemeEvent;

/**
 * ResolvedThemeEvent
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ResolvedThemeEvent extends ThemeEvent
{
    /**
     * Sets a theme
     *
     * @param ThemeInterface $theme A theme
     *
     * @return void
     */
    public function setTheme(ThemeInterface $theme)
    {
        $this->theme = $theme;
    }
}
