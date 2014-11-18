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
 * ThemeNameParser
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeNameParser implements ThemeNameParserInterface
{
    /**
     * @var array
     */
    protected $cache = array();

    /**
     * Converts a given theme name to a theme reference
     *
     * @param string $theme A theme name
     *
     * @return ThemeNameReference
     */
    public function parse($theme)
    {
        if (isset($this->cache[$theme])) {
            return $this->cache[$theme];
        }

        $virtual = false;
        if ('@' == $theme{0}) {
            $theme = substr($theme, 1);
            $virtual = true;
        }

        return $this->cache[$theme] = new ThemeNameReference($theme, $virtual);
    }
}
