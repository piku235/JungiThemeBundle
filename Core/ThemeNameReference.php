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
 * ThemeNameReference represents standard theme names e.g. "footheme", as well virtual theme
 * names e.g. "@bartheme"
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeNameReference implements ThemeNameReferenceInterface
{
    /**
     * @var bool
     */
    protected $virtual;

    /**
     * @var string
     */
    protected $themeName;

    /**
     * Constructor
     *
     * @param string $themeName A theme name
     * @param bool   $virtual   Is a virtual theme? (optional)
     */
    public function __construct($themeName, $virtual = false)
    {
        $this->themeName = $themeName;
        $this->virtual = (bool) $virtual;
    }

    /**
     * Checks if the theme is virtual
     *
     * @return bool
     */
    public function isVirtual()
    {
        return $this->virtual;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return ($this->virtual ? '@' : '').$this->themeName;
    }
}
