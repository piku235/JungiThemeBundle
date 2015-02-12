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
 * VirtualTheme is a representation of themes which are aggregated with this virtual theme
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualTheme implements VirtualThemeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var ThemeInterface
     */
    protected $decorated;

    /**
     * @var ThemeInterface[]
     */
    protected $themes;

    /**
     * Constructor
     *
     * @param string           $name   An unique theme name
     * @param ThemeInterface[] $themes Themes that belongs to the virtual theme
     *
     * @throws \InvalidArgumentException If one of the given themes is not an instance of
     *                                   the ThemeInterface
     * @throws \RuntimeException         When in the children themes there is an another virtual theme
     */
    public function __construct($name, array $themes)
    {
        $this->name = $name;
        $this->decorated = null;

        foreach ($themes as $theme) {
            if (!$theme instanceof ThemeInterface) {
                throw new \InvalidArgumentException('The theme must be an instance of the "ThemeInterface".');
            } elseif ($theme instanceof VirtualThemeInterface) {
                throw new \RuntimeException(sprintf('You cannot attach a virtual theme to an another virtual theme.'));
            }
        }

        $this->themes = $themes;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecoratedTheme(ThemeInterface $parent)
    {
        $this->decorated = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getDecoratedTheme()
    {
        return $this->decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException When the real theme is not set
     */
    public function getPath()
    {
        if (!$this->decorated) {
            throw new \RuntimeException('The path cannot be returned, because the decorated theme is not set.');
        }

        return $this->decorated->getPath();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException When the real theme is not set
     */
    public function getTags()
    {
        if (!$this->decorated) {
            throw new \RuntimeException('The tags cannot be returned, because the decorated theme is not set.');
        }

        return $this->decorated->getTags();
    }

    /**
     * Returns the themes that belongs to the virtual theme
     *
     * @return ThemeInterface[]
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * The string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
