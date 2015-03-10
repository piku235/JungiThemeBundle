<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping;

/**
 * VirtualThemeDefinition.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeDefinition extends ThemeDefinition
{
    /**
     * @var array
     */
    private $themeReferences = array();

    /**
     * Adds a theme reference.
     *
     * @param string $themeName A referenced theme name
     */
    public function addThemeReference($themeName)
    {
        $this->themeReferences[] = $themeName;
    }

    /**
     * Returns the theme references.
     *
     * @return array
     */
    public function getThemeReferences()
    {
        return $this->themeReferences;
    }
}
