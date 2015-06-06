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
use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Core\VirtualTheme;
use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagClassRegistryInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * ThemeBuilder.
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
     * @var TagClassRegistryInterface
     */
    private $tagRegistry;

    /**
     * @var ThemeDefinition[]
     */
    private $themeDefinitions;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Constructor.
     *
     * @param TagClassRegistryInterface $tagReg A tag registry
     */
    public function __construct(TagClassRegistryInterface $tagReg)
    {
        $this->themeDefinitions = array();
        $this->parameters = array();
        $this->tagReflections = array();
        $this->tagRegistry = $tagReg;
    }

    /**
     * Builds themes depending on the contained theme definitions.
     *
     * @return ThemeCollection
     */
    public function build()
    {
        /* @var StandardThemeDefinition[] $standardThemes */
        /* @var VirtualThemeDefinition[] $virtualThemes */
        $relations = array();
        $standardThemes = array();
        $virtualThemes = array();
        $result = new ThemeCollection();

        // Validate themes and separate them
        foreach ($this->themeDefinitions as $name => $definition) {
            if ($definition instanceof VirtualThemeDefinition) {
                $virtualThemes[$name] = $definition;

                // Validate
                foreach ($definition->getThemeReferences() as $childTheme) {
                    if (isset($relations[$childTheme])) {
                        throw new \LogicException(sprintf(
                            'The theme "%s" is currently attached to the virtual theme "%s". You cannot attach the same theme
                            to several virtual themes.',
                            $relations[$childTheme]
                        ));
                    }

                    $relations[$childTheme] = $name;
                }
            } else {
                $standardThemes[$name] = $definition;
            }
        }

        // Creates standard themes
        foreach ($standardThemes as $themeName => $definition) {
            $definition->setParent(isset($relations[$themeName]) ? $relations[$themeName] : null);
            // The theme will be only pushed to collection when
            // it has not got any parent theme (virtual)
            if (!$definition->getParent()) {
                $result->add($this->createStandardTheme($themeName, $definition));
            }
        }

        // Creates virtual themes
        foreach ($virtualThemes as $themeName => $definition) {
            $result->add($this->createVirtualTheme($themeName, $definition));
        }

        return $result;
    }

    /**
     * Creates a standard theme from a given theme definition.
     *
     * @param string                  $themeName  A theme name
     * @param StandardThemeDefinition $definition A definition
     *
     * @return Theme
     */
    private function createStandardTheme($themeName, StandardThemeDefinition $definition)
    {
        return new Theme(
            $themeName,
            $this->locator->locate($definition->getPath()),
            $this->createThemeInfo($definition->getInformation()),
            $this->createTags($definition->getTags())
        );
    }

    /**
     * Creates a virtual theme from a given theme definition.
     *
     * @param string                 $themeName  A theme name
     * @param VirtualThemeDefinition $definition A definition
     *
     * @return VirtualTheme
     */
    private function createVirtualTheme($themeName, VirtualThemeDefinition $definition)
    {
        $themes = array();
        foreach ($definition->getThemeReferences() as $refThemeName) {
            $themeDef = $this->getThemeDefinition($refThemeName);
            if (!$themeDef instanceof StandardThemeDefinition) {
                throw new \LogicException(sprintf(
                    'Virtual themes can consists only of standard themes. Encountered in the theme "%s".',
                    $themeName
                ));
            }

            $themes[] = $this->createStandardTheme($refThemeName, $themeDef);
        }

        return new VirtualTheme(
            $themeName,
            $themes,
            $this->createThemeInfo($definition->getInformation()),
            $this->createTags($definition->getTags())
        );
    }

    /**
     * Creates tags from given tag definitions.
     *
     * @param Tag[] $definitions Definitions
     *
     * @return TagCollection
     */
    private function createTags(array $definitions)
    {
        $tags = new TagCollection();
        foreach ($definitions as $definition) {
            $name = $definition->getName();
            if (!isset($this->tagReflections[$name])) {
                $this->tagReflections[$name] = new \ReflectionClass($this->tagRegistry->getTagClass($name));
            }

            $reflection = $this->tagReflections[$name];
            if ($args = $definition->getArguments()) {
                array_walk_recursive($args, array($this, 'replaceArgument'));
                $tag = $reflection->newInstanceArgs($args);
            } else {
                $tag = $reflection->newInstance();
            }

            $tags->add($tag);
        }

        return $tags;
    }

    /**
     * Creates a ThemeInfo instance based on given definition.
     *
     * @param ThemeInfo $definition A definition
     *
     * @return ThemeInfoEssence
     */
    protected function createThemeInfo(ThemeInfo $definition = null)
    {
        $builder = ThemeInfoEssence::createBuilder();
        if (null !== $definition) {
            if ($definition->hasProperty('name')) {
                $builder->setName($definition->getProperty('name'));
            }
            if ($definition->hasProperty('description')) {
                $builder->setDescription($definition->getProperty('description'));
            }
            if ($definition->hasProperty('authors')) {
                foreach ($definition->getProperty('authors') as $author) {
                    $builder->addAuthor(new Author(
                        $author['name'],
                        $author['email'],
                        isset($author['homepage']) ? $author['homepage'] : null
                    ));
                }
            }
        }

        return $builder->getThemeInfo();
    }
}
