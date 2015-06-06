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
 * LoaderContext.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class LoaderContext
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * @var ThemeDefinitionRegistryInterface
     */
    protected $registry;

    /**
     * Constructor.
     *
     * @param string                           $resource A resource
     * @param ThemeDefinitionRegistryInterface $registry A registry
     */
    public function __construct($resource, ThemeDefinitionRegistryInterface $registry)
    {
        $this->resource = $resource;
        $this->registry = $registry;
    }

    /**
     * Returns the container.
     *
     * @return ThemeDefinitionRegistryInterface
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Returns the resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /*
     * Resolves the real value of a given parameter.
     *
     * @param string $paramName A parameter name
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     *
    public function resolveParameter($paramName)
    {
        if (!$this->hasParameter($paramName)) {
            throw new \RuntimeException(sprintf('The parameter "%s" can not be found.', $paramName));
        }

        return $this->getParameter($paramName);
    }*/
}
