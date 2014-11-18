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

use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;
use Jungi\Bundle\ThemeBundle\Tag\TagCollectionInterface;

/**
 * ThemeManager is a simple implementation of the ThemeManagerInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeManager implements ThemeManagerInterface
{
    /**
     * @var ThemeInterface[]
     */
    protected $themes;

    /**
     * Constructor
     *
     * @param ThemeInterface[] $themes Themes (optional)
     */
    public function __construct($themes = array())
    {
        $this->themes = array();
        foreach ($themes as $theme) {
            $this->addTheme($theme);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTheme(ThemeInterface $theme)
    {
        $this->themes[$theme->getName()] = $theme;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTheme($name)
    {
        return isset($this->themes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTheme($name)
    {
        if (!$this->hasTheme($name)) {
            throw new ThemeNotFoundException($name);
        }

        return $this->themes[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getThemes()
    {
        return array_values($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function findThemeWithTags($tags, $condition = TagCollectionInterface::COND_AND)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        foreach ($this->themes as $theme) {
            if ($theme->getTags()->containsSet($tags, $condition)) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findThemesWithTags($tags, $condition = TagCollectionInterface::COND_AND)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $result = array();
        foreach ($this->themes as $theme) {
            if ($theme->getTags()->containsSet($tags, $condition)) {
                $result[] = $theme;
            }
        }

        return $result;
    }
}
