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
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\TagDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeBuilder;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlFileLoader is responsible for creating theme instances from a yaml mapping file
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class YamlFileLoader extends GenericFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) == 'yml';
    }

    /**
     * Processes parameters
     *
     * @param array        $content A file content
     * @param ThemeBuilder $builder A theme builder
     * @param string       $file    A theme mapping file
     *
     * @return void
     *
     * @throws \InvalidArgumentException If parameters key is not of array type
     */
    private function parseParameters(array $content, ThemeBuilder $builder, $file)
    {
        if (isset($content['parameters'])) {
            if (!is_array($content['parameters'])) {
                throw new \InvalidArgumentException(sprintf(
                    'The "parameters" key should must be an array in the file "%s".',
                    $file
                ));
            }

            $builder->setParameters($content['parameters']);
        }
    }

    /**
     * Parses themes
     *
     * @param array        $content A configuration file content
     * @param ThemeBuilder $builder A theme builder
     * @param string       $file    A theme mapping file
     *
     * @return void
     */
    private function parseThemes(array $content, ThemeBuilder $builder, $file)
    {
        foreach ($content['themes'] as $themeName => $specification) {
            if (!empty($specification['virtual'])) {
                $this->parseVirtualTheme($themeName, $specification, $builder, $file);
            } else {
                $this->parseStandardTheme($themeName, $specification, $builder, $file);
            }
        }
    }

    /**
     * Parses a virtual theme
     *
     * @param string       $themeName     A theme name
     * @param array        $specification A theme specification
     * @param ThemeBuilder $builder       A theme builder
     * @param string       $file          A theme mapping file
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the path key or/and the info key is missing
     * @throws \InvalidArgumentException If some keys are unrecognized
     */
    private function parseVirtualTheme($themeName, array $specification, ThemeBuilder $builder, $file)
    {
        // Validation
        if (!isset($specification['themes'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "themes" key is missing for the theme "%s" specification in the file "%s".',
                $themeName,
                $file
            ));
        } elseif (!is_array($specification['themes'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "themes" key at the theme "%s" must be an array in the file "%s".',
                $themeName,
                $file
            ));
        }

        $def = new VirtualThemeDefinition();
        $this->parseTags($themeName, $specification, $def, $file);
        foreach ($specification['themes'] as $theme) {
            $def->addThemeReference($theme);
        }

        $builder->addThemeDefinition($themeName, $def);
    }

    /**
     * Parses a theme
     *
     * @param string       $themeName     A theme name
     * @param array        $specification A theme specification
     * @param ThemeBuilder $builder       A theme builder
     * @param string       $file          A theme mapping file
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the path key is missing
     */
    private function parseStandardTheme($themeName, array $specification, ThemeBuilder $builder, $file)
    {
        // Validation
        if (!isset($specification['path'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "path" key is missing for the theme "%s" in the file "%s".',
                $themeName,
                $file
            ));
        }

        $def = new StandardThemeDefinition();
        $def->setPath($specification['path']);
        $this->parseTags($themeName, $specification, $def, $file);

        $builder->addThemeDefinition($themeName, $def);
    }

    /**
     * Parses tags specification
     *
     * @param string          $themeName       A theme name
     * @param array           $specification   A specification
     * @param ThemeDefinition $themeDefinition A theme definition
     * @param string          $file            A theme mapping file
     *
     * @return void
     */
    private function parseTags($themeName, array $specification, ThemeDefinition $themeDefinition, $file)
    {
        if (!isset($specification['tags'])) {
            return;
        }

        if (!is_array($specification['tags'])) {
            throw new \InvalidArgumentException(sprintf(
                'The "tags" key at the theme "%s" must be an array in the file "%s".',
                $themeName,
                $file
            ));
        }

        foreach ($specification['tags'] as $tagName => $args) {
            $args = (array) $args;
            array_walk_recursive($args, array($this, 'replaceValue'));

            $def = new TagDefinition();
            $def->setName($tagName);
            $def->setArguments($args);
            $themeDefinition->addTag($def);
        }
    }

    /**
     * Replaces a given argument by its proper php value
     *
     * @param string &$argument An argument
     *
     * @return void
     */
    private function replaceValue(&$argument)
    {
        // Check if it's a constant
        if (0 === strpos($argument, 'const@')) {
            $argument = new Constant(substr($argument, 6));
        }
    }

    /**
     * Validates an entire mapping file
     *
     * @param mixed  $content YAML file content
     * @param string $file    A mapping file
     *
     * @return void
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
     *
     * @throws \InvalidArgumentException If the given file is not readable
     */
    protected function doLoad($path, ThemeBuilder $builder)
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('The given file "%s" is not readable.', $path));
        }

        $content = Yaml::parse(file_get_contents($path), true);
        if (null === $content) { // If is an empty file

            return;
        }

        // Validate a mapping file
        $this->validate($content, $path);

        // Parameters
        $this->parseParameters($content, $builder, $path);

        // Themes
        $this->parseThemes($content, $builder, $path);
    }
}
