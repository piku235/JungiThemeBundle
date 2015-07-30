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

/**
 * Interface for loading theme definitions from specified resource.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface DefinitionLoaderInterface
{
    /**
     * Loads theme definitions from the given resource.
     *
     * @param string $resource A resource
     */
    public function load($resource);

    /**
     * Checks if the given resource is supported.
     *
     * @param string $resource A resource
     * @param string $type     A resource type (optional)
     *
     * @return bool
     */
    public function supports($resource, $type = null);
}
