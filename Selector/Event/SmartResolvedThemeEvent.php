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

/**
 * SmartResolvedThemeEvent additionally allows to clear a theme located in the event
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class SmartResolvedThemeEvent extends ResolvedThemeEvent
{
    /**
     * Clears the current theme and stops the execution of rest listeners
     * Is also equivalent to invalidate the theme
     *
     * It can be useful when the theme did not passed some conditions
     *
     * @return void
     */
    public function clearTheme()
    {
        $this->theme = null;
        $this->stopPropagation();
    }
}
