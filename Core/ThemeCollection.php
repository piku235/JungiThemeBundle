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

use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tag\TagInterface;

/**
 * ThemeCollection.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var ThemeInterface[]
     */
    protected $themes;

    /**
     * Constructor.
     *
     * @param ThemeInterface[] $themes Themes (optional)
     */
    public function __construct(array $themes = array())
    {
        $this->themes = array();
        foreach ($themes as $theme) {
            $this->add($theme);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->themes);
    }

    /**
     * Returns the first theme in the collection.
     *
     * @return ThemeInterface|null
     */
    public function first()
    {
        return reset($this->themes);
    }

    /**
     *
     */
    public function add(ThemeInterface $theme)
    {
        if ($this->has($theme->getName())) {
            throw new \RuntimeException(sprintf('There is already theme with the name "%s".', $theme));
        }

        $this->themes[$theme->getName()] = $theme;
    }

    /**
     *
     */
    public function has($themeName)
    {
        return isset($this->themes[$themeName]);
    }

    /**
     *
     */
    public function contains(ThemeInterface $theme)
    {
        return $this->has($theme->getName());
    }

    /**
     *
     */
    public function get($themeName)
    {
        if (!$this->has($themeName)) {
            throw new ThemeNotFoundException($themeName);
        }

        return $this->themes[$themeName];
    }

    /**
     *
     */
    public function remove($themeName)
    {
        if (!$this->has($themeName)) {
            return;
        }

        unset($this->themes[$themeName]);
    }

    /**
     * @return ThemeInterface[]
     */
    public function all()
    {
        return $this->themes;
    }

    /**
     * Returns the theme which has given tags.
     *
     * @param TagInterface|TagInterface[] $tags      A one tag or tags
     * @param string                      $condition A condition (optional)
     *
     * @return ThemeInterface|null Null if the theme can not be found
     */
    public function findOneByTags($tags, $condition = TagCollection::COND_AND)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        foreach ($this->themes as $name => $theme) {
            if ($theme->getTags()->containsSet($tags, $condition)) {
                return $theme;
            }
        }

        return;
    }

    /**
     * Returns all themes which has given tags.
     *
     * @param TagInterface|TagInterface[] $tags      A one tag or tags
     * @param string                      $condition A condition (optional)
     *
     * @return ThemeInterface[]
     */
    public function findByTags($tags, $condition = TagCollection::COND_AND)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $result = array();
        foreach ($this->themes as $name => $theme) {
            if ($theme->getTags()->containsSet($tags, $condition)) {
                $result[] = $theme;
            }
        }

        return $result;
    }
}
