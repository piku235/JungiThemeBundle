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

use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;

/**
 * Interface for loading themes from specified resource.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Loads themes from a given resource.
     *
     * @param string $resource A resource
     *
     * @return ThemeCollection
     */
    public function load($resource);

    /**
     * Checks if a given resource is supported.
     *
     * @param string $resource A resource
     *
     * @return bool
     */
    public function supports($resource);
}
