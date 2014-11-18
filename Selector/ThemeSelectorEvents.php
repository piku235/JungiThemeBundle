<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Selector;

/**
 * ThemeSelectorEvents is a basic class which holds all theme selector events
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class ThemeSelectorEvents
{
    /**
     * @var string
     */
    const RESOLVED_THEME = 'jungi_theme.selector.resolved_theme';

    /**
     * @var string
     */
    const SELECTED_THEME = 'jungi_theme.selector.selected_theme';
}
