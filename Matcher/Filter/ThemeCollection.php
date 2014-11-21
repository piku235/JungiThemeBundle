<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Matcher\Filter;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;

/**
 * ThemeCollection
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
     * Constructor
     *
     * @param ThemeInterface[] $themes Themes
     *
     * @throws \InvalidArgumentException When there is an invalid theme instance
     */
    public function __construct(array $themes)
    {
        $this->themes = array();
        foreach ($themes as $theme) {
            if (!$theme instanceof ThemeInterface) {
                throw new \InvalidArgumentException('The theme must be an instance of the "Jungi\Bundle\ThemeBundle\Core\ThemeInterface".');
            }

            $this->themes[$theme->getName()] = $theme;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->themes);
    }

    /**
     * Removes a given theme from the collection
     *
     * @param ThemeInterface $theme A theme
     *
     * @return void
     */
    public function remove(ThemeInterface $theme)
    {
        if (!isset($this->themes[$theme->getName()])) {
            return;
        }

        unset($this->themes[$theme->getName()]);
    }

    /**
     * Returns the first theme in the collection
     *
     * @return ThemeInterface|null
     */
    public function first()
    {
        return reset($this->themes);
    }
}
