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

/**
 * ThemeManager is a simple implementation of the ThemeManagerInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeManager implements ThemeManagerInterface
{
    /**
     * @var ThemeInterface[]
     */
    protected $themes;

    /**
     * Constructor
     *
     * @param ThemeInterface[] $themes Themes (optional)
     */
    public function __construct($themes = array())
    {
        $this->themes = array();
        foreach ($themes as $theme) {
            $this->addTheme($theme);
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface::addTheme()
     */
    public function addTheme(ThemeInterface $theme)
    {
        $this->themes[] = $theme;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface::hasTheme()
     */
    public function hasTheme($name)
    {
        foreach ($this->themes as $theme) {
            if ($theme->getName() == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface::getTheme()
     */
    public function getTheme($name)
    {
        foreach ($this->themes as $theme) {
            if ($theme->getName() == $name) {
                return $theme;
            }
        }

        throw new ThemeNotFoundException($name);
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface::getTheme()
     */
    public function getThemeWithTags($tags)
    {
        foreach ($this->themes as $theme) {
            if ($theme->getTags()->contains($tags)) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface::getTheme()
     */
    public function getThemesWithTags($tags)
    {
        $result = array();
        foreach ($this->themes as $theme) {
            if ($theme->getTags()->contains($tags)) {
                $result[] = $theme;
            }
        }

        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface::getThemes()
     */
    public function getThemes()
    {
        return $this->themes;
    }
}