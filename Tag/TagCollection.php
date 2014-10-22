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
 * TagCollection provides features for flexible operations on theme tags
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var TagInterface[]
     */
    protected $tags;

    /**
     * Constructor
     *
     * @param TagInterface[] $tags Tags (optional)
     *
     * @throws \InvalidArgumentException If one of tags is not a Tag instance
     */
    public function __construct(array $tags = array())
    {
        $this->tags = array();
        foreach ($tags as $tag) {
            if (!$tag instanceof TagInterface) {
                throw new \InvalidArgumentException('The one of tags is not a Tag instance.');
            }

            $this->tags[$tag->getName()] = $tag;
        }
    }

    /**
     * Returns the iterator with all tags in the collection
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->tags);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->tags);
    }

    /**
     * Returns a tag by tag name
     *
     * @param string $name A tag name
     *
     * @return TagInterface
     *
     * @throws \RuntimeException When there is no tag with a given tag name
     */
    public function get($name)
    {
        if (!isset($this->tags[$name])) {
            throw new \RuntimeException(sprintf('A tag with the name "%s" can not be found in the collection.', $name));
        }

        return $this->tags[$name];
    }

    /**
     * Checks if a given tag name or names exists
     *
     * Be careful, because this method ONLY looks for a given tag name
     * and it does not check if the tag is EQUAL to a found tag
     *
     * @param string|array $names A tag name or tag names
     *
     * @return boolean
     */
    public function has($names)
    {
        foreach ((array) $names as $name) {
            if (!isset($this->tags[$name])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if a given tag or collection of tags exists and if they are EQUAL to the found tags
     *
     * @param TagInterface|TagInterface[] $tags A tag or a collection of tags
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given tag or tags has bad type
     */
    public function contains($tags)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        foreach ($tags as $tag) {
            if (!$tag instanceof TagInterface) {
                throw new \InvalidArgumentException('Only TagInterface instances are allowed.');
            } elseif (!isset($this->tags[$tag->getName()]) || !$tag->isEqual($this->tags[$tag->getName()])) {
                return false;
            }
        }

        return true;
    }
}
