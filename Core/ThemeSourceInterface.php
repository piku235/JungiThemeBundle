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
use Jungi\Bundle\ThemeBundle\Tag\TagInterface;

/**
 * This interface is used to manage all themes.
 *
 * A theme source is a central place where all used themes are gathered. You can
 * add new themes, get a particular theme or get all available themes.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeSourceInterface
{
    /**
     * Adds a new theme.
     *
     * @param ThemeInterface $theme A theme
     */
    public function addTheme(ThemeInterface $theme);

    /**
     * Checks if the given theme exists.
     *
     * @param string $name A theme name
     *
     * @return bool
     */
    public function hasTheme($name);

    /**
     * Returns the theme by name.
     *
     * @param string $name A theme name
     *
     * @return ThemeInterface
     *
     * @throws \Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException
     */
    public function getTheme($name);

    /**
     * Returns all themes.
     *
     * @return ThemeInterface[]
     */
    public function getThemes();

    /**
     * Returns the theme which has given tags.
     *
     * @param TagInterface|TagInterface[] $tags      A single tag or tags
     * @param string                      $condition A condition (optional)
     *
     * @return ThemeInterface|null Null if the theme can not be found
     */
    public function findThemeWithTags($tags, $condition = TagCollection::COND_AND);

    /**
     * Returns all themes which has given tags.
     *
     * @param TagInterface|TagInterface[] $tags      A single tag or tags
     * @param string                      $condition A condition (optional)
     *
     * @return ThemeInterface[]
     */
    public function findThemesWithTags($tags, $condition = TagCollection::COND_AND);
}
