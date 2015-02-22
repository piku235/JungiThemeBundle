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

use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;
use Jungi\Bundle\ThemeBundle\Core\VirtualTheme;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistryInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tag\TagInterface;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * ThemeBuilder
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeBuilder
{
    /**
     * @var \ReflectionObject[]
     */
    private $tagReflections;

    /**
     * @var TagRegistryInterface
     */
    private $tagRegistry;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    /**
     * @var ThemeDefinition[]
     */
    private $themeDefinitions;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Constructor
     *
     * @param TagRegistryInterface $tagReg  A tag registry
     * @param FileLocatorInterface $locator A file locator
     */
    public function __construct(TagRegistryInterface $tagReg, FileLocatorInterface $locator)
    {
        $this->themeDefinitions = array();
        $this->parameters = array();
        $this->tagReflections = array();
        $this->tagRegistry = $tagReg;
        $this->locator = $locator;
    }

    /**
     * Adds a theme definition
     *
     * @param string          $name       A theme name
     * @param ThemeDefinition $definition A theme definition
     *
     * @return void
     *
     * @throws \RuntimeException If there is a theme definition under the same name
     */
    public function addThemeDefinition($name, ThemeDefinition $definition)
    {
        if ($this->hasThemeDefinition($name)) {
            throw new \RuntimeException(sprintf('There is already registered theme definition under the name "%s".', $name));
        }

        $this->themeDefinitions[$name] = $definition;
    }

    /**
     * @param string $name A theme name
     *
     * @return bool
     */
    public function hasThemeDefinition($name)
    {
        return isset($this->themeDefinitions[$name]);
    }

    /**
     * Returns the given theme definition
     *
     * @param string $name A theme name
     *
     * @return ThemeDefinition|null Null if the theme doesn't exist
     *
     * @throws \RuntimeException When the given theme definition does not exist
     */
    public function getThemeDefinition($name)
    {
        if (!isset($this->themeDefinitions[$name])) {
            throw new \RuntimeException(sprintf('The theme definition "%s" can not be found.', $name));
        }

        return $this->themeDefinitions[$name];
    }

    /**
     * Returns the all registered theme definitions
     *
     * @return ThemeDefinition[]
     */
    public function getThemeDefinitions()
    {
        return $this->themeDefinitions;
    }

    /**
     * Sets parameters
     *
     * @param array $params Parameters
     *
     * @return void
     */
    public function setParameters(array $params)
    {
        $this->parameters = $params;
    }

    /**
     * Sets a parameter
     *
     * @param string $name  A name
     * @param mixed  $value A value
     *
     * @return void
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Checks if a given parameter exists
     *
     * @param string $name A name
     *
     * @return bool
     */
    public function hasParameter($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * Returns the parameter value
     *
     * @param string $name A name
     *
     * @return mixed Null if it doesn't exist
     */
    public function getParameter($name)
    {
        if (!$this->hasParameter($name)) {
            return null;
        }

        return $this->parameters[$name];
    }

    /**
     * Returns the all parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Builds themes depending on the contained theme definitions
     *
     * @param ThemeRegistryInterface $registry A theme registry
     *
     * @return void
     */
    public function build(ThemeRegistryInterface $registry)
    {
        /* @var StandardThemeDefinition[] $standardThemes */
        /* @var VirtualThemeDefinition[] $virtualThemes */
        $themeReferences = array();
        $relations = array();
        $standardThemes = array();
        $virtualThemes = array();

        // Validate themes and separate them
        foreach ($this->themeDefinitions as $name => $definition) {
            $this->validateThemeDefinition($name, $definition);
            if ($definition instanceof VirtualThemeDefinition) {
                foreach ($definition->getThemeReferences() as $refThemeName) {
                    $relations[$refThemeName] = $name;
                }

                $virtualThemes[$name] = $definition;
            } else {
                $standardThemes[$name] = $definition;
            }
        }

        // Creates standard themes
        foreach ($standardThemes as $themeName => $definition) {
            $virtualTheme = isset($relations[$themeName]) ? $relations[$themeName] : null;
            $theme = new Theme(
                $themeName,
                $this->locator->locate($definition->getPath()),
                $this->processTagDefinitions($definition->getTags()),
                $virtualTheme
            );
            // The theme will be only pushed to registry when
            // it has not got any parent theme (virtual)
            if ($virtualTheme) {
                if (!isset($themeReferences[$virtualTheme])) {
                    $themeReferences[$virtualTheme] = array();
                }

                $themeReferences[$virtualTheme][] = $theme;
            } else {
                $registry->registerTheme($theme);
            }
        }

        // Creates virtual themes
        foreach ($virtualThemes as $themeName => $definition) {
            $registry->registerTheme(new VirtualTheme(
                $themeName,
                $themeReferences[$themeName],
                $this->processTagDefinitions($definition->getTags())
            ));
        }
    }

    /**
     * Validates a given theme definition
     *
     * @param string          $themeName  A theme name
     * @param ThemeDefinition $definition A theme definition
     *
     * @return void
     *
     * @throws \LogicException When the virtual definition has references to an another
     *                         virtual theme
     */
    private function validateThemeDefinition($themeName, ThemeDefinition $definition)
    {
        if ($definition instanceof VirtualThemeDefinition) {
            foreach ($definition->getThemeReferences() as $refThemeName) {
                $themeDef = $this->getThemeDefinition($refThemeName);
                if ($themeDef instanceof VirtualThemeDefinition) {
                    throw new \LogicException(sprintf(
                        'Referencing to virtual themes is not allowed. Encountered at the theme "%s".',
                        $themeName
                    ));
                }
            }
        }
    }

    /**
     * @param TagDefinition[] $definitions
     *
     * @return TagCollection
     */
    private function processTagDefinitions(array $definitions)
    {
        $tags = new TagCollection();
        foreach ($definitions as $definition) {
            $tags->add($this->processTagDefinition($definition));
        }

        return $tags;
    }

    /**
     * @param TagDefinition $definition
     *
     * @return TagInterface
     */
    private function processTagDefinition(TagDefinition $definition)
    {
        $name = $definition->getName();
        if (!isset($this->tagReflections[$name])) {
            $this->tagReflections[$name] = new \ReflectionClass($this->tagRegistry->getTag($name));
        }

        $reflection = $this->tagReflections[$name];
        if ($args = $definition->getArguments()) {
            array_walk_recursive($args, array($this, 'replaceArgument'));
            return $reflection->newInstanceArgs($args);
        }

        return $reflection->newInstance();
    }

    /**
     * @param string &$arg An argument
     *
     * @return void
     */
    private function replaceArgument(&$arg)
    {
        if ($arg instanceof Constant) {
            $arg = $this->resolveConstant($arg);
        } elseif (preg_match('/^%([^\s%]+)%$/', $arg, $matches)) {
            $arg = $this->resolveParameter($matches[1]);
        }
    }

    /**
     * Resolved the real value of a given parameter reference
     *
     * @param string $paramName A parameter name
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     */
    private function resolveParameter($paramName)
    {
        if (!$this->hasParameter($paramName)) {
            throw new \RuntimeException(sprintf('The parameter "%s" can not be found.', $paramName));
        }

        return $this->getParameter($paramName);
    }

    /**
     * Resolves the value of a given constant
     *
     * @param Constant $const A constant
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException When the constant is wrong or not found
     */
    private function resolveConstant(Constant $const)
    {
        $val = $const->getValue();
        if (defined($val)) {
            return constant($val);
        }

        // Is the constant located in a class or a tag type?
        if (false !== $pos = strrpos($val, '::')) {
            // A class or a tag type
            $location = substr($val, 0, $pos);
            if (false !== strpos($location, '.')) { // Is the location of a tag?
                $location = $this->tagRegistry->getTag($location);
            }

            $realConst = $location.substr($val, $pos);
            if (defined($realConst)) {
                return constant($realConst);
            }
        }

        throw new \InvalidArgumentException(sprintf('The constant "%s" is wrong or it can not be found.', $val));
    }
}
