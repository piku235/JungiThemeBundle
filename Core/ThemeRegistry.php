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

use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * ThemeRegistry is a simple implementation of the ThemeRegistryInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeRegistry implements ThemeRegistryInterface
{
    /**
     * @var ThemeCollection
     */
    protected $themes;

    /**
     * Constructor
     *
     * @param ThemeInterface[] $themes Themes (optional)
     */
    public function __construct(array $themes = array())
    {
        $this->themes = new ThemeCollection($themes);
    }

    /**
     * {@inheritdoc}
     */
    public function registerTheme(ThemeInterface $theme)
    {
        $this->themes->add($theme);
    }

    /**
     * {@inheritdoc}
     */
    public function hasTheme($name)
    {
        return $this->themes->has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getTheme($name)
    {
        return $this->themes->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getThemes()
    {
        return $this->themes->all();
    }

    /**
     * {@inheritdoc}
     */
    public function findThemeWithTags($tags, $condition = TagCollection::COND_AND)
    {
        return $this->themes->findOneByTags($tags, $condition);
    }

    /**
     * {@inheritdoc}
     */
    public function findThemesWithTags($tags, $condition = TagCollection::COND_AND)
    {
        return $this->themes->findByTags($tags, $condition);
    }
}
