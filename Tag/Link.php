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
 * Link tag takes the role of a pointer to another theme.
 *
 * Generally the tag is used by AWD (Adaptive Web Design)
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Link implements TagInterface
{
    /**
     * @var string
     */
    protected $theme;

    /**
     * Constructor
     *
     * @param string $theme A pointed theme name
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Returns the pointed theme name
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
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
        return 'jungi.link';
    }
}
