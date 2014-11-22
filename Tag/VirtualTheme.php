<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag;

/**
 * VirtualTheme tag is used to connect multiple themes into one
 *
 * Generally the tag is used by AWD (Adaptive Web Design)
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualTheme implements TagInterface
{
    /**
     * @var string
     */
    protected $themeName;

    /**
     * Constructor
     *
     * @param string $themeName A virtual theme name
     */
    public function __construct($themeName)
    {
        $this->themeName = $themeName;
    }

    /**
     * Returns the virtual theme name
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqual(TagInterface $tag)
    {
        return $tag == $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'jungi.virtual_theme';
    }
}
