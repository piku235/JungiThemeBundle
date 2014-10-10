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
 * The basic interface which every theme must have.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeInterface
{
    /**
     * Returns the unique theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the theme tag collection
     *
     * @return \Jungi\Bundle\ThemeBundle\Tag\TagCollection
     */
    public function getTags();

    /**
     * Returns the absolute path to the theme resources
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the details of the theme
     *
     * @return \Jungi\Bundle\ThemeBundle\Details\DetailsInterface
     */
    public function getDetails();
}
