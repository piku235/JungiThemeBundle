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
class TagCollection implements \IteratorAggregate
{
    /**
     * @var string
     */
    const COND_AND = 'and';

    /**
     * @var string
     */
    const COND_OR = 'or';

    /**
     * @var TagInterface[]
     */
    protected $tags;

    /**
     * Constructor
     *
     * @param TagInterface[] $tags Tags (optional)
     */
    public function __construct(array $tags = array())
    {
        $this->tags = array();
        foreach ($tags as $tag) {
            $this->add($tag);
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
     * Adds a new tag
     *
     * @param TagInterface $tag A tag
     *
     * @return void
     */
    public function add(TagInterface $tag)
    {
        $this->tags[$tag->getName()] = $tag;
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
     * Checks if a given tag name exists
     *
     * Be careful, because this method ONLY looks for a given tag name
     * and it does not check if the tag is EQUAL to a found tag
     *
     * @param string $name A tag name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->tags[$name]);
    }

    /**
     * The same as method "has" with the difference that it can
     * iterate over given tag names
     *
     * @param array  $names     Tag names
     * @param string $condition A condition (optional)
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given condition is incorrect
     */
    public function hasSet(array $names, $condition = self::COND_AND)
    {
        switch ($condition) {
            case self::COND_AND:
                foreach ($names as $name) {
                    if (!$this->has($name)) {
                        return false;
                    }
                }

                return true;
            case self::COND_OR:
                foreach ($names as $name) {
                    if ($this->has($name)) {
                        return true;
                    }
                }

                return false;
            default:
                throw new \InvalidArgumentException(sprintf(
                    'The chosen condition "%s" is incorrect. The supported conditions are only: "%s".',
                    $condition,
                    implode(', ', array(self::COND_OR, self::COND_AND))
                ));
        }
    }

    /**
     * Checks if a given tag or collection of tags exists and if they are EQUAL to the found tags
     *
     * @param TagInterface $tag A tag
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given tag or tags has bad type
     */
    public function contains(TagInterface $tag)
    {
        return $this->has($tag->getName()) && $tag->isEqual($this->tags[$tag->getName()]);
    }

    /**
     * The same as method "contains" with the difference that it can
     * iterate over given tags
     *
     * @param array  $tags      Tags
     * @param string $condition A condition (optional)
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given condition is incorrect
     */
    public function containsSet(array $tags, $condition = self::COND_AND)
    {
        switch ($condition) {
            case self::COND_AND:
                foreach ($tags as $tag) {
                    if (!$this->contains($tag)) {
                        return false;
                    }
                }

                return true;
            case self::COND_OR:
                foreach ($tags as $tag) {
                    if ($this->contains($tag)) {
                        return true;
                    }
                }

                return false;
            default:
                throw new \InvalidArgumentException(sprintf(
                    'The chosen condition "%s" is incorrect. The supported conditions are only: "%s".',
                    $condition,
                    implode(', ', array(self::COND_OR, self::COND_AND))
                ));
        }
    }

    /**
     * Merges an another tag collection with the current collection
     *
     * @param TagCollection $collection A tag collection
     *
     * @return void
     */
    public function merge(TagCollection $collection)
    {
        foreach ($collection as $tag) {
            $this->add($tag);
        }
    }

    /**
     * Removes a given tag from the collection
     *
     * @param string $name A tag name
     *
     * @return void
     */
    public function remove($name)
    {
        if (!$this->has($name)) {
            return;
        }

        unset($this->tags[$name]);
    }

    /**
     * Returns all tags
     *
     * @return TagInterface[]
     */
    public function all()
    {
        return $this->tags;
    }
}
