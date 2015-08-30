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
 * ThemeDefinition.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeDefinition
{
    /**
     * @var Tag[]
     */
    protected $tags = array();

    /**
     * @var ThemeInfo
     */
    protected $info;

    /**
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param string    $path A path (optional)
     * @param Tag[]     $tags Tag definitions (optional)
     * @param ThemeInfo $info A theme info (optional)
     */
    public function __construct($path = null, array $tags = array(), ThemeInfo $info = null)
    {
        $this->path = $path;
        $this->info = $info;
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * Sets a path to theme resources.
     *
     * @param $path
     *
     * @return ThemeDefinition
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Returns the path to theme resources.
     *
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param array $tags
     *
     * @return ThemeDefinition
     */
    public function setTags(array $tags)
    {
        $this->tags = array();
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * Adds a tag definition.
     *
     * @param Tag $definition
     *
     * @return ThemeDefinition
     */
    public function addTag(Tag $definition)
    {
        $this->tags[] = $definition;

        return $this;
    }

    /**
     * Returns the tag definitions.
     *
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets a theme info definition.
     *
     * @param ThemeInfo $definition
     *
     * @return ThemeDefinition
     */
    public function setInfo(ThemeInfo $definition)
    {
        $this->info = $definition;

        return $this;
    }

    /**
     * Returns the theme info definition.
     *
     * @return ThemeInfo
     */
    public function getInfo()
    {
        return $this->info;
    }
}
