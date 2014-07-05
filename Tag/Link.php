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
 * Link tag takes the role of a pointer to another theme
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Link implements TagInterface
{
    /**
     * @var string
     */
    protected $themeName;

    /**
     * Constructor
     *
     * @param string $theme A pointed theme name
     */
    public function __construct($theme)
    {
        $this->themeName = $theme;
    }

    /**
     * Returns the pointed theme name
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Tag\TagInterface::isEqual()
     */
    public function isEqual(TagInterface $tag)
    {
        return $tag == $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Tag\TagInterface::getName()
     */
    public static function getName()
    {
        return 'jungi.link';
    }
}