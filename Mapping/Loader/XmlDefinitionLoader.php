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
use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Symfony\Component\Config\Util\XmlUtils;

/**
 * XmlDefinitionLoader is responsible for loading theme definitions from xml mapping files.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class XmlDefinitionLoader extends AbstractDefinitionLoader
{
    /**
     * @var string
     */
    const NS = 'http://piku235.github.io/JungiThemeBundle/schema/theme-mapping';

    /**
     * {@inheritdoc}
     */
    public function supports($file, $type = null)
    {
        return 'xml' == pathinfo($file, PATHINFO_EXTENSION) || 'xml' === $type;
    }

    /**
     * Parses parameters from a DOM document.
     *
     * @param \DOMElement      $elm     DOM element
     * @param XmlLoaderContext $context Context
     */
    private function parseParameters(\DOMElement $elm, XmlLoaderContext $context)
    {
        /* @var \Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistryInterface $container */
        $container = $context->getRegistry();
        $container->setParameters($this->getElementsAsPhp($elm, 'parameter', $context));
    }

    /**
     * Parses themes from a DOM document.
     *
     * @param \DOMElement      $elm     DOM element
     * @param XmlLoaderContext $context Context
     */
    private function parseThemes(\DOMElement $elm, XmlLoaderContext $context)
    {
        // Parse standard themes by first
        foreach ($context->xpath('mapping:theme', $elm) as $child) {
            $this->parseStandardTheme($child, $context);
        }

        // Parse virtual themes as last
        foreach ($context->xpath('mapping:virtual-theme', $elm) as $child) {
            $this->parseVirtualTheme($child, $context);
        }
    }

    /**
     * Parses a theme element from a DOM document.
     *
     * @param \DOMElement      $elm     A DOM element
     * @param XmlLoaderContext $context Context
     *
     * @throws \InvalidArgumentException If a theme node has some missing attributes
     */
    private function parseStandardTheme(\DOMElement $elm, XmlLoaderContext $context)
    {
        $definition = new ThemeDefinition();
        $definition->setPath($elm->getAttribute('path'));
        $this->parseInfo($elm, $definition, $context);
        $this->parseTags($elm, $definition, $context);

        $context->getRegistry()->registerThemeDefinition($elm->getAttribute('name'), $definition);
    }

    /**
     * Parses a theme element from a DOM document.
     *
     * @param \DOMElement      $elm     A DOM element
     * @param XmlLoaderContext $context Context
     *
     * @throws \InvalidArgumentException If a theme node has some missing attributes
     */
    private function parseVirtualTheme(\DOMElement $elm, XmlLoaderContext $context)
    {
        $definition = new VirtualThemeDefinition();
        $this->parseInfo($elm, $definition, $context);
        $this->parseTags($elm, $definition, $context);
        foreach ($context->xpath('mapping:themes/mapping:ref', $elm) as $ref) {
            /* @var \DOMElement $ref */

            $definition->addThemeReference(new Reference(
                $ref->getAttribute('theme'),
                $ref->getAttribute('as')
            ));
        }

        $context->getRegistry()->registerThemeDefinition($elm->getAttribute('name'), $definition);
    }

    /**
     * Parses a theme info from the given DOM element.
     *
     * @param \DOMElement      $elm     A DOM element
     * @param ThemeDefinition  $theme   A theme definition
     * @param XmlLoaderContext $context Context
     */
    private function parseInfo(\DOMElement $elm, ThemeDefinition $theme, XmlLoaderContext $context)
    {
        $properties = $context->xpath('mapping:info/mapping:property', $elm);
        if (!$properties->length) {
            return;
        }

        $definition = new ThemeInfo();
        foreach ($properties as $property) {
            /* @var \DOMElement $property */
            $definition->setProperty(
                $property->getAttribute('key'),
                $this->getElementAsPhp($property, 'property', $context)
            );
        }

        $theme->setInfo($definition);
    }

    /**
     * Parses a theme tags from the given DOM element.
     *
     * @param \DOMElement      $elm     A DOM element
     * @param ThemeDefinition  $theme   A theme definition
     * @param XmlLoaderContext $context Context
     */
    private function parseTags(\DOMElement $elm, ThemeDefinition $theme, XmlLoaderContext $context)
    {
        foreach ($context->xpath('mapping:tags/mapping:tag', $elm) as $tag) {
            /* @var \DOMElement $tag */

            $definition = new Tag($tag->getAttribute('name'));
            $arguments = $this->getElementsAsPhp($tag, 'argument', $context);
            if (!$arguments && $tag->nodeValue) {
                $arguments = array($tag->nodeValue);
            }
            $definition->setArguments($arguments);

            $theme->addTag($definition);
        }
    }

    /**
     * Returns elements with a proper php value.
     *
     * @param \DOMElement      $elm     A DOM element
     * @param string           $name    Child element name
     * @param XmlLoaderContext $context Context
     *
     * @return mixed
     */
    private function getElementsAsPhp(\DOMElement $elm, $name, XmlLoaderContext $context)
    {
        $i = 0;
        $elements = array();
        foreach ($elm->childNodes as $child) {
            if (!$child instanceof \DOMElement || $child->localName !== $name || $child->namespaceURI !== self::NS) {
                continue;
            }

            $key = $i;
            if ($child->hasAttribute('key')) {
                $key = $child->getAttribute('key');
            } else {
                ++$i;
            }

            $elements[$key] = $this->getElementAsPhp($child, $name, $context);
        }

        return $elements;
    }

    /**
     * Returns arguments to the proper php values.
     *
     * @param \DOMElement      $elm     A DOM element
     * @param string           $name    Child element name
     * @param XmlLoaderContext $context Context
     *
     * @return mixed
     */
    private function getElementAsPhp(\DOMElement $elm, $name, XmlLoaderContext $context)
    {
        switch ($elm->getAttribute('type')) {
            case 'collection':
                return $this->getElementsAsPhp($elm, $name, $context);
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
    protected function doLoad($file)
    {
        // DOMDocument
        $doc = $this->loadFile($file);

        // Context
        $xpath = new \DOMXPath($doc);
        $xpath->registerNamespace('mapping', self::NS);
        $context = new XmlLoaderContext($file, new ParametricThemeDefinitionRegistry(), $xpath);

        foreach ($doc->documentElement->childNodes as $child) {
            if (!$child instanceof \DOMElement || $child->namespaceURI !== self::NS) {
                continue;
            }

            switch ($child->localName) {
                case 'parameters':
                    $this->parseParameters($child, $context);
                    break;
                case 'themes':
                    $this->parseThemes($child, $context);
                    break;
            }
        }

        return $context->getRegistry();
    }

    /**
     * Loads a xml file data.
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
