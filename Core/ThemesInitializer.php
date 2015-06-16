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

use Jungi\Bundle\ThemeBundle\Mapping\Dumper\PhpDumper;
use Jungi\Bundle\ThemeBundle\Mapping\Loader\DefinitionLoaderInterface;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Resource\FileResource;

/**
 * The initializer job is to load all registered themes.
 *
 * @see Jungi\Bundle\ThemeBundle\DependencyInjection\JungiThemeExtension::registerMappingFile
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class ThemesInitializer
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var DefinitionLoaderInterface[]
     */
    private $loaders;

    /**
     * @var ThemeSourceInterface
     */
    private $source;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var PhpDumper
     */
    private $dumper;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    /**
     * @var ThemeDefinitionRegistryInterface
     */
    private $definitionRegistry;

    /**
     * Constructor.
     *
     * @param array                            $paths              Theme mapping files
     * @param DefinitionLoaderInterface[]      $loaders            A theme mapping loaders
     * @param ThemeDefinitionRegistryInterface $definitionRegistry A theme definition registry
     * @param ThemeSourceInterface             $source             A theme source
     * @param PhpDumper                        $dumper             A php dumper
     * @param FileLocatorInterface             $locator            A locator
     * @param bool                             $debug              Debugging mode
     * @param string                           $cacheDir           A cache directory
     *
     * @throws \InvalidArgumentException When cache directory does not exist
     */
    public function __construct(array $paths, array $loaders, ThemeDefinitionRegistryInterface $definitionRegistry, ThemeSourceInterface $source, PhpDumper $dumper, FileLocatorInterface $locator, $debug, $cacheDir)
    {
        if (!is_dir($cacheDir)) {
            throw new \InvalidArgumentException(sprintf('The cache directory "%s" does not exist.', $cacheDir));
        }

        $this->paths = $paths;
        $this->loaders = $loaders;
        $this->source = $source;
        $this->debug = $debug;
        $this->cacheDir = $cacheDir;
        $this->definitionRegistry = $definitionRegistry;
        $this->dumper = $dumper;
        $this->locator = $locator;
    }

    /**
     * Loads themes from previously set theme mapping files to the theme registry.
     */
    public function initialize()
    {
        // Cache
        $cacheFile = new ConfigCache($this->cacheDir.'/jungi_themes.php', $this->debug);
        if (!$cacheFile->isFresh()) {
            foreach ($this->paths as list($path, $type)) {
                $loader = $this->resolveLoader($path, $type);
                $loader->load($path);
            }

            $locator = $this->locator;
            $files = array_map(function ($path) use ($locator) {
                return new FileResource($locator->locate($path[0]));
            }, $this->paths);
            $cacheFile->write($this->dumper->dump($this->definitionRegistry), $files);
        }

        // Loads the cache
        $collection = require $cacheFile->getPath();

        try {
            foreach ($collection as $theme) {
                $this->source->addTheme($theme);
            }
        } catch (\RuntimeException $e) {
            // theme exist
        }
    }

    /**
     * Resolved a theme mapping loader for a given resource.
     *
     * @param string $path A path
     * @param string $type A type of theme mapping file
     *
     * @return DefinitionLoaderInterface
     *
     * @throws \RuntimeException When there is no matching loader for the given path
     */
    private function resolveLoader($path, $type)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($path, $type)) {
                return $loader;
            }
        }

        throw new \RuntimeException(sprintf('Unable to find a theme mapping loader for the file "%s".', $path));
    }
}
