<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag;

/**
 * TagClassRegistry is a simple implementation of the TagClassRegistryInterface.
 *
 * We can say that this tag registry is a little dumb, because it does not verifies
 * if a registered tag name is appropriate with the name of a tag class.
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
     * @param array $classes Tag classes (optional)
     */
    public function __construct(array $classes = array())
    {
        foreach ($classes as $tagName => $class) {
            $this->registerTagClass($tagName, $class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerTagClass($name, $class)
    {
        $this->classes[$name] = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTagClass($name)
    {
        return isset($this->classes[$name]);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getTagClasses()
    {
        return $this->classes;
    }
}
