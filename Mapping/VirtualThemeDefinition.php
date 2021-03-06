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
 * VirtualThemeDefinition.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeDefinition extends ThemeDefinition
{
    /**
     * @var Reference[]
     */
    private $references;

    /**
     * @var ThemeDefinition[]
     */
    private $themes;

    /**
     * Constructor.
     *
     * @param array     $tags Tag definitions (optional)
     * @param ThemeInfo $info A theme info (optional)
     */
    public function __construct(array $tags = array(), ThemeInfo $info = null)
    {
        parent::__construct(null, $tags, $info);

        $this->themes = array();
        $this->references = array();
    }

    /**
     * Not used.
     */
    public function setPath($path)
    {
        throw new \LogicException('You cannot set path of a virtual theme.');
    }

    /**
     * Not used.
     */
    public function getPath()
    {
        throw new \LogicException('You cannot get path of a virtual theme.');
    }

    /**
     * Adds a child theme reference.
     *
     * @param Reference $reference A theme reference
     *
     * @return VirtualThemeDefinition
     */
    public function addThemeReference(Reference $reference)
    {
        $this->references[$reference->getThemeName()] = $reference;

        return $this;
    }

    /**
     * Returns the child theme references.
     *
     * @return Reference[]
     */
    public function getThemeReferences()
    {
        return $this->references;
    }

    /**
     * Clears the theme references contained in the definition.
     */
    public function clearThemeReferences()
    {
        $this->references = array();
    }

    /**
     * Adds a child theme definition.
     *
     * @param string          $name       A theme name
     * @param ThemeDefinition $definition A theme definition
     *
     * @return VirtualThemeDefinition
     */
    public function addTheme($name, ThemeDefinition $definition)
    {
        $this->themes[$name] = $definition;

        return $this;
    }

    /**
     * Returns the given theme.
     *
     * @param string $name A theme name
     *
     * @return ThemeDefinition
     *
     * @throws \InvalidArgumentException
     */
    public function getTheme($name)
    {
        if (!isset($this->themes[$name])) {
            throw new \InvalidArgumentException(sprintf('The theme "%s" can not be found.'));
        }

        return $this->themes[$name];
    }

    /**
     * Removes the given theme.
     *
     * @param string $name A theme name
     */
    public function removeTheme($name)
    {
        unset($this->themes[$name]);
    }

    /**
     * Returns the all theme definitions.
     *
     * @return ThemeDefinition[]
     */
    public function getThemes()
    {
        return $this->themes;
    }
}
