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
 * ThemeRegistry is a simple implementation of the ThemeRegistryInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeRegistry implements ThemeRegistryInterface
{
    /**
     * @var array
     */
    private $nonpublic;

    /**
     * @var ThemeInterface[]
     */
    protected $themes;

    /**
     * Constructor
     *
     * @param ThemeInterface[] $themes Themes (optional)
     */
    public function __construct(array $themes = array())
    {
        $this->nonpublic = array();
        $this->themes = array();
        foreach ($themes as $theme) {
            $this->registerTheme($theme);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerTheme(ThemeInterface $theme)
    {
        $name = $theme->getName();
        if ($this->hasTheme($name)) {
            throw new \RuntimeException(sprintf('There is already theme with the name "%s".', $name));
        }

        $this->themes[$name] = $theme;
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
        return $this->themes;
    }

    /**
     * {@inheritdoc}
     */
    public function findThemeWithTags($tags, $condition = TagCollectionInterface::COND_AND)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        foreach ($this->themes as $name => $theme) {
            if (in_array($name, $this->nonpublic)) {
                continue;
            }
            if ($theme->getTags()->containsSet($tags, $condition)) {
                return $theme;
            }
        }

        return;
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
        foreach ($this->themes as $name => $theme) {
            if (in_array($name, $this->nonpublic)) {
                continue;
            }
            if ($theme->getTags()->containsSet($tags, $condition)) {
                $result[] = $theme;
            }
        }

        return $result;
    }
}
