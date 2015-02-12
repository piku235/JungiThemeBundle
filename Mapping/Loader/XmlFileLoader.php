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
use Jungi\Bundle\ThemeBundle\Mapping\TagDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeBuilder;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Symfony\Component\Config\Util\XmlUtils;

/**
 * XmlFileLoader is responsible for creating theme instances from a xml mapping file
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class XmlFileLoader extends GenericFileLoader
{
    /**
     * @var string
     */
    const NS = 'http://piku235.github.io/JungiThemeBundle/schema/theme-mapping';

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) == 'xml';
    }

    /**
     * Parses parameters from a DOM document
     *
     * @param \DOMElement  $elm     DOM element
     * @param ThemeBuilder $builder A theme builder
     * @param object       $context Context
     *
     * @return void
     */
    private function parseParameters(\DOMElement $elm, ThemeBuilder $builder, $context)
    {
        $builder->setParameters($this->getElementsAsPhp($elm, 'parameter'));
    }

    /**
     * Parses themes from a DOM document
     *
     * @param \DOMElement  $elm     DOM element
     * @param ThemeBuilder $builder A theme builder
     * @param object       $context Context
     *
     * @return void
     */
    private function parseThemes(\DOMElement $elm, ThemeBuilder $builder, $context)
    {
        foreach ($context->xpath->query('*', $elm) as $child) {
            switch ($child->localName) {
                case 'theme':
                    // Standard themes
                    $this->parseStandardTheme($child, $builder, $context);

                    break;
                case 'virtual-theme':
                    // Virtual themes
                    $this->parseVirtualTheme($child, $builder, $context);

                    break;
            }
        }
    }

    /**
     * Parses a theme element from a DOM document
     *
     * @param \DOMElement  $elm     A DOM element
     * @param ThemeBuilder $builder A theme builder
     * @param object       $context Context
     *
     * @return void
     *
     * @throws \InvalidArgumentException If a theme node has some missing attributes
     */
    private function parseStandardTheme(\DOMElement $elm, ThemeBuilder $builder, $context)
    {
        $definition = new ThemeDefinition();
        $definition->setPath($elm->getAttribute('path'));
        $this->parseTags($elm, $definition, $context);

        $builder->addThemeDefinition($elm->getAttribute('name'), $definition);
    }

    /**
     * Parses a theme element from a DOM document
     *
     * @param \DOMElement  $elm     A DOM element
     * @param ThemeBuilder $builder A theme builder
     * @param object       $context Context
     *
     * @return void
     *
     * @throws \InvalidArgumentException If a theme node has some missing attributes
     */
    private function parseVirtualTheme(\DOMElement $elm, ThemeBuilder $builder, $context)
    {
        $definition = new VirtualThemeDefinition();
        foreach ($context->xpath->query('mapping:themes/mapping:ref', $elm) as $ref) {
            /* @var \DOMElement $ref */

            $definition->addThemeReference($ref->getAttribute('theme'));
        }

        $builder->addThemeDefinition($elm->getAttribute('name'), $definition);
    }

    /**
     * Parses a theme tags from a given DOM element
     *
     * @param \DOMElement     $elm   A DOM element
     * @param ThemeDefinition $theme A theme definition
     * @param object       $context Context
     *
     * @return void
     */
    private function parseTags(\DOMElement $elm, ThemeDefinition $theme, $context)
    {
        foreach ($context->xpath->query('mapping:tags/mapping:tag', $elm) as $tag) {
            /* @var \DOMElement $tag */

            $definition = new TagDefinition();
            $definition->setName($tag->getAttribute('name'));
            $arguments = $this->getElementsAsPhp($tag, 'argument');
            if (!$arguments && $tag->nodeValue) {
                $arguments = array($tag->nodeValue);
            }
            $definition->setArguments($arguments);

            $theme->addTag($definition);
        }
    }

    /**
     * Returns elements with a proper php value
     *
     * @param \DOMElement $elm  A DOM element
     * @param string      $name Child element name
     *
     * @return mixed
     */
    private function getElementsAsPhp(\DOMElement $elm, $name)
    {
        $i = 0;
        $elements = array();
        foreach ($elm->getElementsByTagNameNS(self::NS, $name) as $child) {
            $key = $i++;
            if ($child->hasAttribute('key')) {
                $key = $child->getAttribute('key');
            }

            $elements[$key] = $this->getElementAsPhp($child, $name);
        }

        return $elements;
    }

    /**
     * Returns arguments to the proper php values
     *
     * @param \DOMElement $elm  A DOM element
     * @param string      $name Child element name
     *
     * @return mixed
     */
    private function getElementAsPhp(\DOMElement $elm, $name)
    {
        switch ($elm->getAttribute('type')) {
            case 'collection':
                return $this->getElementsAsPhp($elm, $name);
            case 'string':
                return $elm->nodeValue;
            case 'constant':
                return new Constant($elm->nodeValue);
            default:
                return XmlUtils::phpize($elm->nodeValue);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doLoad($path, ThemeBuilder $builder)
    {
        $doc = $this->loadFile($path);
        $context = new \stdClass();
        $context->file = $path;
        $context->xpath = new \DOMXPath($doc);
        $context->xpath->registerNamespace('mapping', self::NS);

        foreach ($context->xpath->query('*') as $child) {
            switch ($child->localName) {
                case 'parameters':
                    $this->parseParameters($child, $builder, $context);
                    break;
                case 'themes':
                    $this->parseThemes($child, $builder, $context);
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unknown element "%s" in the file "%s".', $child->localName, $path));
            }
        }
    }

    /**
     * Loads a xml file data
     *
     * @param string $file A file
     *
     * @return \DOMDocument
     *
     * @throws \RuntimeException When the some problem will occur while parsing a mapping file
     */
    protected function loadFile($file)
    {
        try {
            $doc = XmlUtils::loadFile($file, __DIR__.'/schema/theme-1.0.xsd');
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException(
                sprintf('The problem has occurred while parsing the file "%s", see the previous exception.', $file),
                null,
                $e
            );
        }

        return $doc;
    }
}
