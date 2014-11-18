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
 * ThemeNameParserInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeNameParserInterface
{
    /**
     * Converts a given theme name to a theme reference
     *
     * @param string $theme A theme name
     *
     * @return ThemeNameReferenceInterface
     */
    public function parse($theme);
}
