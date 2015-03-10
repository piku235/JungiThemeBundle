<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

use Jungi\Bundle\ThemeBundle\Mapping\Loader\LoaderInterface;

/**
 * The autoloader job is to load all registered themes in the configuration.
 *
 * @see Jungi\Bundle\ThemeBundle\DependencyInjection\JungiThemeExtension::registerMappingFile
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class AutomaticLoader
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var LoaderInterface[]
     */
    private $loaders;

    /**
     * @var ThemeRegistryInterface
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param array                  $paths    Theme mapping files
     * @param LoaderInterface[]      $loaders  A theme mapping loaders
     * @param ThemeRegistryInterface $registry A theme registry
     */
    public function __construct(array $paths, array $loaders, ThemeRegistryInterface $registry)
    {
        $this->paths = $paths;
        $this->loaders = $loaders;
        $this->registry = $registry;
    }

    /**
     * Loads themes from previously set theme mapping files to the theme registry.
     */
    public function load()
    {
        foreach ($this->paths as list($path, $type)) {
            $loader = $this->resolveLoader($path, $type);
            foreach ($loader->load($path) as $theme) {
                $this->registry->registerTheme($theme);
            }
        }
    }

    /**
     * Resolved a theme mapping loader for a given resource.
     *
     * @param string $path A path
     * @param string $type A type of theme mapping file
     *
     * @return LoaderInterface
     *
     * @throws \RuntimeException When there is no matching loader for the given path
     */
    private function resolveLoader($path, $type)
    {
        if ($type !== null) {
            if (isset($this->loaders[$type])) {
                return $this->loaders[$type];
            }
        } else {
            foreach ($this->loaders as $loader) {
                if ($loader->supports($path)) {
                    return $loader;
                }
            }
        }

        throw new \RuntimeException(sprintf('Unable to find a theme mapping loader for the file "%s".', $path));
    }
}
