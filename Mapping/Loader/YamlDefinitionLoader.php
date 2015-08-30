<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Loader;

use Jungi\Bundle\ThemeBundle\Mapping\Constant;
use Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\ProcessorInterface;
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * YamlDefinitionLoader is responsible for creating theme instances from yaml mapping files.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class YamlDefinitionLoader extends AbstractDefinitionLoader
{
    /**
     * @var YamlParser
     */
    private $parser;

    /**
     * Constructor.
     *
     * @param ProcessorInterface               $processor A processor
     * @param ThemeDefinitionRegistryInterface $registry  A theme definition registry
     * @param FileLocatorInterface             $locator   A file locator
     */
    public function __construct(ProcessorInterface $processor, ThemeDefinitionRegistryInterface $registry, FileLocatorInterface $locator)
    {
        $this->parser = new YamlParser();

        parent::__construct($processor, $registry, $locator);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file, $type = null)
    {
        return 'yml' == pathinfo($file, PATHINFO_EXTENSION) || 'yml' === $type;
    }

    /**
     * Processes parameters.
     *
     * @param array         $content A file content
     * @param LoaderContext $context A loader context
     *
     * @throws \InvalidArgumentException If parameters key is not of array type
     */
    private function parseParameters(array $content, LoaderContext $context)
    {
        if (isset($content['parameters'])) {
            if (!is_array($content['parameters'])) {
                throw new \InvalidArgumentException(sprintf(
                    'The "parameters" key should must be an array in the file "%s".',
                    $context->getResource()
                ));
            }

            // Walk over parameters
            array_walk_recursive($content['parameters'], array($this, 'replaceValue'));

            /** @var \Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistryInterface $container */
            $container = $context->getRegistry();
            $container->setParameters($content['parameters']);
        }
    }

    /**
     * Parses themes.
     *
     * @param array         $content A configuration file content
     * @param LoaderContext $context A loader context
     */
    private function parseThemes(array $content, LoaderContext $context)
    {
        foreach ($content['themes'] as $themeName => $specification) {
            if (!empty($specification['is_virtual'])) {
                $this->parseVirtualTheme($themeName, $specification, $context);
            } else {
                $this->parseStandardTheme($themeName, $specification, $context);
            }
        }
    }

    /**
     * Parses a virtual theme.
     *
     * @param string        $themeName     A theme name
     * @param array         $specification A theme specification
     * @param LoaderContext $context       A loader context
     *
     * @throws \InvalidArgumentException If the path key or/and the info key is missing
     * @throws \InvalidArgumentException If some keys are unrecognized
     * @throws \InvalidArgumentException If the key "theme" is missing for a theme reference
     */
    private function parseVirtualTheme($themeName, array $specification, LoaderContext $context)
    {
        // Validation
        if (!isset($specification['themes'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "themes" key is missing for the theme "%s" specification in the file "%s".',
                $themeName,
                $context->getResource()
            ));
        } elseif (!is_array($specification['themes'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "themes" key at the theme "%s" must be an array in the file "%s".',
                $themeName,
                $context->getResource()
            ));
        }

        $def = new VirtualThemeDefinition();
        $this->parseInfo($themeName, $specification, $def, $context);
        $this->parseTags($themeName, $specification, $def, $context);
        foreach ($specification['themes'] as $theme) {
            // It is an extended version?
            if (is_array($theme)) {
                if (!isset($theme['theme'])) {
                    throw new \InvalidArgumentException(sprintf(
                        'There is missing key "theme" for a theme reference under the theme "%s" in the file "%s".',
                        $themeName,
                        $context->getResource()
                    ));
                }

                $def->addThemeReference(new Reference($theme['theme'], isset($theme['as']) ? $theme['as'] : null));
            } else {
                $def->addThemeReference(new Reference($theme));
            }
        }

        $context->getRegistry()->registerThemeDefinition($themeName, $def);
    }

    /**
     * Parses a theme.
     *
     * @param string        $themeName     A theme name
     * @param array         $specification A theme specification
     * @param LoaderContext $context       A loader context
     *
     * @throws \InvalidArgumentException If the path key is missing
     */
    private function parseStandardTheme($themeName, array $specification, LoaderContext $context)
    {
        // Validation
        if (!isset($specification['path'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "path" key is missing for the theme "%s" in the file "%s".',
                $themeName,
                $context->getResource()
            ));
        }

        $def = new ThemeDefinition();
        $def->setPath($specification['path']);
        $this->parseInfo($themeName, $specification, $def, $context);
        $this->parseTags($themeName, $specification, $def, $context);

        $context->getRegistry()->registerThemeDefinition($themeName, $def);
    }

    /**
     * Parses a theme info from the given DOM element.
     *
     * @param string          $themeName     A theme name
     * @param array           $specification A specification
     * @param ThemeDefinition $themeDef      A theme definition
     * @param LoaderContext   $context       A loader context
     */
    private function parseInfo($themeName, array $specification, ThemeDefinition $themeDef, LoaderContext $context)
    {
        if (!isset($specification['info'])) {
            return;
        }
        if (!is_array($specification['info'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "info" key at the theme "%s" must be an array in the file "%s".',
                $themeName,
                $context->getResource()
            ));
        }

        $definition = new ThemeInfo();
        foreach ($specification['info'] as $property => $value) {
            $definition->setProperty($property, $value);
        }

        $themeDef->setInfo($definition);
    }

    /**
     * Parses tags specification.
     *
     * @param string          $themeName     A theme name
     * @param array           $specification A specification
     * @param ThemeDefinition $themeDef      A theme definition
     * @param LoaderContext   $context       A loader context
     */
    private function parseTags($themeName, array $specification, ThemeDefinition $themeDef, LoaderContext $context)
    {
        if (!isset($specification['tags'])) {
            return;
        }
        if (!is_array($specification['tags'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "tags" key at the theme "%s" must be an array in the file "%s".',
                $themeName,
                $context->getResource()
            ));
        }

        foreach ($specification['tags'] as $tagName => $args) {
            $args = (array) $args;
            array_walk_recursive($args, array($this, 'replaceValue'));

            $def = new Tag();
            $def->setName($tagName);
            $def->setArguments($args);
            $themeDef->addTag($def);
        }
    }

    /**
     * Replaces the given argument by its proper php value.
     *
     * @param string &$argument An argument
     */
    private function replaceValue(&$argument)
    {
        // Check if it's a constant
        if (0 === strpos($argument, 'const@')) {
            $argument = new Constant(substr($argument, 6));
        }
    }

    /**
     * Validates an entire mapping file.
     *
     * @param mixed  $content YAML file content
     * @param string $file    A mapping file
     *
     * @throws \InvalidArgumentException When themes node is not defined
     * @throws \UnexpectedValueException When a content from the YAML file returns other data type than array
     */
    private function validate($content, $file)
    {
        if (!is_array($content)) {
            throw new \UnexpectedValueException(sprintf('The content of the mapping file "%s" must be of the YAML array type.', $file));
        } elseif (!array_key_exists('themes', $content)) {
            throw new \InvalidArgumentException(sprintf('There is missing "themes" node in the theme mapping file "%s".', $file));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doLoad($path)
    {
        $content = $this->parser->parse(file_get_contents($path));
        if (null === $content) { // If the file is empty
            return;
        }

        // Context
        $context = new LoaderContext($path, new ParametricThemeDefinitionRegistry());

        // Validate a mapping file
        $this->validate($content, $path);

        // Parameters
        $this->parseParameters($content, $context);

        // Themes
        $this->parseThemes($content, $context);

        return $context->getRegistry();
    }
}
