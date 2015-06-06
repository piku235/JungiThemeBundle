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

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * XmlLoaderContext.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class XmlLoaderContext extends LoaderContext
{
    /**
     * @var \DOMXPath
     */
    private $xpath;

    /**
     * Constructor.
     *
     * @param string                           $resource A resource
     * @param ThemeDefinitionRegistryInterface $registry A registry
     * @param \DOMXPath                        $xpath    A DOM xpath
     */
    public function __construct($resource, ThemeDefinitionRegistryInterface $registry, \DOMXPath $xpath)
    {
        $this->xpath = $xpath;

        parent::__construct($resource, $registry);
    }

    /**
     * Return the DOMXPath instance.
     *
     * @return \DOMXPath
     */
    public function getXpath()
    {
        return $this->xpath;
    }

    /**
     * Shorthand version of DOMXpath::query.
     *
     * @param string   $expression
     * @param \DOMNode $contextnode
     *
     * @return \DOMNodeList
     *
     * @see \DOMXPath::query
     */
    public function xpath($expression, $contextnode = null)
    {
        return $this->xpath->query($expression, $contextnode);
    }
}
