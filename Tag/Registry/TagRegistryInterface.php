<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag\Registry;

/**
 * Classes with this interface are responsible for returning registered tag classes
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface TagRegistryInterface
{
    /**
     * Checks if a given tag name has a registered class
     *
     * @param string $name A tag name
     *
     * @return boolean
     */
    public function hasTagClass($name);

    /**
     * Gets a class of a given tag name
     *
     * @param string $name A tag name
     *
     * @return string
     *
     * @throws \InvalidArgumentException When a given tag name does not exists
     */
    public function getTagClass($name);

    /**
     * Returns all registered tag classes
     *
     * @return array
     */
    public function getTagClasses();
}