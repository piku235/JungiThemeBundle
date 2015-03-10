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
 * The basic theme interface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeInterface
{
    /**
     * Returns the unique theme name.
     *
     * A theme name should be as simple as possible e.g. "footheme", "bar-theme"
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the theme tag collection.
     *
     * @return \Jungi\Bundle\ThemeBundle\Tag\TagCollection
     */
    public function getTags();

    /**
     * Returns the absolute path to the theme directory.
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the parent theme name.
     *
     * @return string|null Null if does not have a parent theme
     */
    public function getParent();
}
