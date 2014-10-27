<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag\Factory;

/**
 * The implemented classes are responsible for creating tags based on a passed tag type
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface TagFactoryInterface
{
    /**
     * Creates the tag by given tag name
     *
     * @param string       $name      A tag name
     * @param string|array $arguments Arguments or an argument for the tag (optional)
     *
     * @return string
     */
    public function create($name, $arguments = null);
}
