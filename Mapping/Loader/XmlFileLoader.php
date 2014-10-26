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

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactoryInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tag\TagInterface;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Util\XmlUtils;

/**
 * XmlFileLoader is responsible for creating theme instances from a xml mapping file
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class XmlFileLoader extends FileLoader
{
    /**
     * @var string
     */
    const NS = 'http://piku235.github.io/JungiThemeBundle/schema/theme-mapping';

    /**
     * @var LoaderHelper
     */
    private $helper;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var \DOMXpath
     */
    private $xpath;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface $themeManager A theme manager
     * @param FileLocatorInterface  $locator      A file locator
     * @param TagFactoryInterface   $factory      A tag factory
     * @param LoaderHelper          $helper       A loader helper
     */
    public function __construct(ThemeManagerInterface $themeManager, FileLocatorInterface $locator, TagFactoryInterface $factory, LoaderHelper $helper)
    {
        parent::__construct($themeManager, $locator, $factory);

        $this->helper = $helper;
        $this->parameters = array();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) == 'xml';
    }

    /**
     * Loads themes from a given xml theme mapping file
     *
     * @param string $file A file
     *
     * @return void
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);

        $doc = $this->loadFile($path);
        $this->xpath = new \DOMXPath($doc);
        $this->xpath->registerNamespace('mapping', self::NS);

        // Parameters
        $this->parseParameters($doc);

        // Themes
        $this->parseThemes($doc);

        // Clear tmp
        $this->parameters = array();
        $this->xpath = null;
    }

    /**
     * Parses parameters from a DOM document
     *
     * @param \DOMDocument $doc A DOM document
     *
     * @return void
     */
    private function parseParameters(\DOMDocument $doc)
    {
        $parameters = $this->getElements($doc->documentElement, 'parameters');
        if (!$parameters) {
            return;
        }

        $this->parameters = $this->getElementsAsPhp($parameters[0], 'parameter');
    }

    /**
     * Parses themes from a DOM document
     *
     * @param \DOMDocument $doc A DOM document
     *
     * @return void
     */
    private function parseThemes(\DOMDocument $doc)
    {
        $themes = $this->getElements($doc->documentElement, 'themes');
        foreach ($this->getElements($themes[0], 'theme') as $child) {
            $this->themeManager->addTheme($this->parseTheme($child));
        }
    }

    /**
     * Parses a theme element from a DOM document
     *
     * @param \DOMElement $elm A DOM element
     *
     * @return Theme
     *
     * @throws \InvalidArgumentException If a theme node has some missing attributes
     */
    private function parseTheme(\DOMElement $elm)
    {
        return new Theme(
            $elm->getAttribute('name'),
            $this->locator->locate($elm->getAttribute('path')),
            $this->parseInfo($elm),
            $this->parseTags($elm)
        );
    }

    /**
     * Parses a info about a theme
     *
     * @param \DOMElement $elm A DOM element
     *
     * @return ThemeInfoEssence
     */
    private function parseInfo(\DOMElement $elm)
    {
        $collection = array();
        foreach ($this->xpath->query('mapping:info/mapping:property', $elm) as $property) {
            list($name, $value) = $this->parseInfoProperty($property);
            $collection[$name] = $value;
        }

        $builder = ThemeInfoEssence::createBuilder();
        $builder->addAuthors($this->processAuthors($collection));
        if (isset($collection['license'])) {
            $builder->setLicense($collection['license']);
        }
        if (isset($collection['description'])) {
            $builder->setDescription($collection['description']);
        }
        if (isset($collection['name'])) {
            $builder->setName($collection['name']);
        }
        if (isset($collection['version'])) {
            $builder->setVersion($collection['version']);
        }

        return $builder->getInformation();
    }

    /**
     * Parses an authors array
     *
     * @param array $collection A collection of properties
     *
     * @return Author[]
     *
     * @throws \InvalidArgumentException If the "authors" is not an array
     * @throws \InvalidArgumentException If the "author" has unrecognized keys
     */
    private function processAuthors(array $collection)
    {
        $authors = array();
        if (isset($collection['authors'])) {
            if (!is_array($collection['authors'])) {
                throw new \InvalidArgumentException('The "authors" property should be a collection.');
            }

            foreach ($collection['authors'] as $author) {
                if (!is_array($author)) {
                    throw new \InvalidArgumentException('The "author" property should be a collection.');
                } elseif ($diff = array_diff(array_keys($author), array('homepage', 'name', 'email'))) {
                    throw new \InvalidArgumentException(sprintf(
                        'The "author" property is invalid, the following keys are unrecognized: "%s".',
                        implode(', ', $diff)
                    ));
                }

                $name = isset($author['name']) ? $author['name'] : null;
                $email = isset($author['email']) ? $author['email'] : null;
                $homepage = isset($author['homepage']) ? $author['homepage'] : null;
                $authors[] = new Author($name, $email, $homepage);
            }
        }

        return $authors;
    }

    /**
     * Parses a info property
     *
     * @param \DOMElement $elm A DOM element
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function parseInfoProperty(\DOMElement $elm)
    {
        $validKeys = array('authors', 'description', 'name', 'version', 'thumbnail', 'screen', 'license');
        if (!$elm->hasAttribute('key')) {
            throw new \InvalidArgumentException(
                'The "property" element of the "info" has not defined the attribute "key". Have you forgot about that?'
            );
        } elseif (!in_array($elm->getAttribute('key'), $validKeys)) {
            throw new \InvalidArgumentException(sprintf(
                'The property key "%s" is invalid, expected one of the following: "%s".',
                $elm->getAttribute('key'),
                implode(', ', $validKeys)
            ));
        }

        return array($elm->getAttribute('key'), $this->getElementAsPhp($elm, 'property', true));
    }

    /**
     * Parses a theme tags from a given DOM element
     *
     * @param \DOMElement $elm A DOM element
     *
     * @return TagCollection
     */
    private function parseTags(\DOMElement $elm)
    {
        $tags = array();
        foreach ($this->xpath->query('mapping:tags/mapping:tag', $elm) as $tag) {
            $tags[] = $this->parseTag($tag);
        }

        return new TagCollection($tags);
    }

    /**
     * Parses a theme tags from a given DOM element
     *
     * @param \DOMElement $elm A DOM element
     *
     * @return TagInterface
     */
    private function parseTag(\DOMElement $elm)
    {
        $arguments = $this->getElementsAsPhp($elm, 'argument', true);
        if (!$arguments) {
            $arguments = $elm->nodeValue;
        }

        return $this->tagFactory->create($elm->getAttribute('name'), $arguments);
    }

    /**
     * Returns the children of a given DOM element
     *
     * @param \DOMElement $elm  A DOM element
     * @param string      $name Child element name
     *
     * @return \DOMElement[]
     */
    private function getElements(\DOMElement $elm, $name = null)
    {
        $collection = array();
        foreach ($elm->childNodes as $child) {
            if ($child instanceof \DOMElement
                && $child->namespaceURI == self::NS
                && (!$name || $name == $child->localName)
            ) {
                $collection[] = $child;
            }
        }

        return $collection;
    }

    /**
     * Returns elements with a proper php value
     *
     * @param \DOMElement $elm               A DOM element
     * @param string      $name              Child element name
     * @param bool        $replaceParameters Whether to replace parameters (optional)
     *
     * @return mixed
     */
    private function getElementsAsPhp(\DOMElement $elm, $name, $replaceParameters = false)
    {
        $i = 0;
        $elements = array();
        foreach ($this->getElements($elm, $name) as $child) {
            $key = $i;
            if ($child->hasAttribute('key')) {
                $key = $child->getAttribute('key');
            }

            $elements[$key] = $this->getElementAsPhp($child, $name, $replaceParameters);
            if (!$child->hasAttribute('key')) {
                $i++;
            }
        }

        return $elements;
    }

    /**
     * Returns arguments to the proper php values
     *
     * @param \DOMElement $elm               A DOM element
     * @param string      $name              Child element name
     * @param bool        $replaceParameters Whether to replace parameters (optional)
     *
     * @return mixed
     */
    private function getElementAsPhp(\DOMElement $elm, $name, $replaceParameters = false)
    {
        switch ($elm->getAttribute('type')) {
            case 'collection':
                return $this->getElementsAsPhp($elm, $name, $replaceParameters);
            case 'string':
                return $elm->nodeValue;
            case 'constant':
                return $this->helper->resolveConstant($elm->nodeValue);
            default:
                $arg = $elm->nodeValue;
                if ($replaceParameters && preg_match('/^%([^%]+)%$/', $arg, $matches)) {
                    $realArg = $matches[1];
                    if (!isset($this->parameters[$realArg])) {
                        throw new \InvalidArgumentException(sprintf('The parameter "%s" is not defined in the theme mapping file.', $realArg));
                    }

                    return $this->parameters[$realArg];
                }

                return XmlUtils::phpize($arg);
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
     * @throws \RuntimeException If a file is not local
     * @throws \DomainException  If a given file is not supported
     */
    protected function loadFile($file)
    {
        if (!stream_is_local($file)) {
            throw new \RuntimeException(sprintf('The "%s" file is not local.', $file));
        } elseif (!$this->supports($file)) {
            throw new \DomainException(sprintf('The given file "%s" is not supported.', $file));
        }

        try {
            $doc = XmlUtils::loadFile($file, __DIR__ . '/schema/theme-1.0.xsd');
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException(sprintf('The problem has occurred while parsing the file "%s", see the previous exception.', $file), null, $e);
        }

        return $doc;
    }
}
