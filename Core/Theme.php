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

use Jungi\Bundle\ThemeBundle\Information\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * Theme is a simple implementation of the ThemeInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Theme implements ThemeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var ThemeInfo
     */
    protected $info;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * Constructor
     *
     * @param string        $name An unique theme name
     * @param string        $path A path to theme resources
     * @param ThemeInfo     $info A ThemeInfo instance
     * @param TagCollection $tags A tag collection (optional)
     */
    public function __construct($name, $path, ThemeInfo $info, TagCollection $tags = null)
    {
        $this->name = $name;
        $this->path = $path;
        $this->info = $info;
        $this->tags = $tags ?: new TagCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getInformation()
    {
        return $this->info;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * The string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
