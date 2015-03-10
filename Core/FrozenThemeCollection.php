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

/**
 * FrozenThemeCollection.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FrozenThemeCollection extends ThemeCollection
{
    /**
     * Constructor.
     *
     * @param ThemeInterface[] $themes Themes (optional)
     */
    public function __construct(array $themes = array())
    {
        $this->themes = array();
        foreach ($themes as $theme) {
            parent::add($theme);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException
     */
    public function add(ThemeInterface $theme)
    {
        throw new \LogicException('This collection is frozen, so you cannot add any theme.');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException
     */
    public function remove($themeName)
    {
        throw new \LogicException('This collection is frozen, so you cannot remove any theme.');
    }
}
