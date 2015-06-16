<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping;

/**
 * Reference.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Reference
{
    /**
     * @var string
     */
    private $themeName;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * Construct.
     *
     * A theme alias is used for shorter theme name, so in further steps
     * it replaces a theme name
     *
     * @param string $themeName A theme name
     * @param string $alias     A theme alias (optional)
     */
    public function __construct($themeName, $alias = null)
    {
        $this->themeName = $themeName;
        $this->alias = $alias;
    }

    /**
     * Returns the referenced theme name.
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * Sets a referenced theme name.
     *
     * @param string $name
     *
     * @return Reference
     */
    public function setThemeName($name)
    {
        $this->themeName = $name;

        return $this;
    }

    /**
     * Returns the alias under which theme will be accessible.
     *
     * @return null|string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets an alias.
     *
     * @param null|string $alias
     *
     * @return Reference
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }
}
