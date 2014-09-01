<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Changer;

/**
 * Theme changer events
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class ThemeChangerEvents
{
    /**
     * @var string
     */
    const PRE_CHANGE = 'jungi_theme_changer.pre_set';

    /**
     * @var string
     */
    const POST_CHANGE = 'jungi_theme_changer.post_set';
}
