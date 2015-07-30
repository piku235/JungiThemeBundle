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
 * TagClassRegistry is a simple implementation of the TagClassRegistryInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagClassRegistry implements TagClassRegistryInterface
{
    /**
     * @var array
     */
    protected $classes = array();

    /**
     * Constructor.
     *
     * @param array $classes Tag classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    /**
     * Checks if the given tag name has the registered class.
     *
     * @param string $name A tag name
     *
     * @return bool
     */
    public function hasTagClass($name)
    {
        return isset($this->classes[$name]);
    }

    /**
     * Gets the class of the given tag name.
     *
     * @param string $name A tag name
     *
     * @return string
     *
     * @throws \InvalidArgumentException When the given tag name does not exists
     */
    public function getTagClass($name)
    {
        if (!$this->hasTagClass($name)) {
            throw new \InvalidArgumentException(
                sprintf('The given tag name "%s" was not registered.', $name)
            );
        }

        return $this->classes[$name];
    }

    /**
     * Returns all registered tag classes.
     *
     * @return array
     */
    public function getTagClasses()
    {
        return $this->classes;
    }
}
