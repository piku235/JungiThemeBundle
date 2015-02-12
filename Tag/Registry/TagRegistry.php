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
 * TagRegistry is a simple implementation of the TagRegistryInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagRegistry implements TagRegistryInterface
{
    /**
     * @var array
     */
    protected $classes = array();

    /**
     * Registers a tag class or tag classes
     *
     * @param string|TagProvider|array $class a TagProvider instance, a collection or a single fully
     *                                        qualified class name
     *
     * @return void
     *
     * @throws \RuntimeException         When the tag class is not exist
     * @throws \InvalidArgumentException When the given tag class does not implement the TagInterface
     */
    public function registerTag($class)
    {
        if ($class instanceof TagProvider) {
            $class = $class->dump();
        }

        foreach ((array) $class as $child) {
            $child = '\\'.ltrim($child, '\\');
            if (!class_exists($child)) {
                throw new \RuntimeException(sprintf('The tag with the class "%s" is not exist.', $child));
            }

            $reflection = new \ReflectionClass($child);
            if (!$reflection->implementsInterface('Jungi\Bundle\ThemeBundle\Tag\TagInterface')) {
                throw new \InvalidArgumentException(
                    sprintf('The given tag class "%s" must implement the interface "Jungi\Bundle\ThemeBundle\Tag\TagInterface".', $child)
                );
            }

            $this->classes[$reflection->getMethod('getName')->invoke(null)] = $child;
        }
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
