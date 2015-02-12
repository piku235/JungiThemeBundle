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
class TagCollection implements \IteratorAggregate, TagCollectionInterface
{
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
     * {@inheritdoc}
     */
    public function add(TagInterface $tag)
    {
        $this->tags[$tag->getName()] = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!isset($this->tags[$name])) {
            throw new \RuntimeException(sprintf('A tag with the name "%s" can not be found in the collection.', $name));
        }

        return $this->tags[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->tags[$name]);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function contains(TagInterface $tag)
    {
        return $this->has($tag->getName()) && $tag->isEqual($this->tags[$tag->getName()]);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function merge(TagCollectionInterface $collection)
    {
        foreach ($collection as $tag) {
            $this->add($tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->tags;
    }
}
