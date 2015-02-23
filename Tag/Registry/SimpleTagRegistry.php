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
 * SimpleTagRegistry is a simple implementation of the TagRegistryInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class SimpleTagRegistry implements TagRegistryInterface
{
    /**
     * @var array
     */
    protected $classes = array();

    /**
     * Constructor
     *
     * @param array $classes Tag classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    /**
     * Checks if a given tag name has the registered class
     *
     * @param string $name A tag name
     *
     * @return boolean
     */
    public function hasTag($name)
    {
        return isset($this->classes[$name]);
    }

    /**
     * Gets the class of a given tag name
     *
     * @param string $name A tag name
     *
     * @return string
     *
     * @throws \InvalidArgumentException When a given tag name does not exists
     */
    public function getTag($name)
    {
        if (!$this->hasTag($name)) {
            throw new \InvalidArgumentException(
                sprintf('The given tag name "%s" was not registered.', $name)
            );
        }

        return $this->classes[$name];
    }

    /**
     * Returns all registered tag classes
     *
     * @return array
     */
    public function getTags()
    {
        return $this->classes;
    }
}
