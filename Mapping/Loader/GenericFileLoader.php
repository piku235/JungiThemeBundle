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

use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeBuilder;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistryInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * GenericFileLoader
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class GenericFileLoader extends FileLoader
{
    /**
     * @var TagRegistryInterface
     */
    private $tagRegistry;

    /**
     * Constructor
     *
     * @param ThemeRegistryInterface $themeReg A theme registry
     * @param TagRegistryInterface   $tagReg   A tag registry
     * @param FileLocatorInterface   $locator  A file locator
     */
    public function __construct(ThemeRegistryInterface $themeReg, TagRegistryInterface $tagReg, FileLocatorInterface $locator)
    {
        $this->tagRegistry = $tagReg;

        parent::__construct($themeReg, $locator);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If a file is not readable
     * @throws \RuntimeException If an exception will be thrown while building themes
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);
        $builder = $this->createThemeBuilder();

        // Loads the file
        $this->doLoad($path, $builder);

        // Register created themes to global theme registry
        try {
            $builder->build($this->themeRegistry);
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf(
                'The problem occurred while building themes from the file "%s", see the previous exception for more details.',
                $path
            ), null, $e);
        }
    }

    /**
     * Creates a new theme builder instance
     *
     * @return ThemeBuilder
     */
    private function createThemeBuilder()
    {
        return new ThemeBuilder($this->tagRegistry, $this->locator);
    }

    /**
     * Performs the main load action
     *
     * @param string       $path    A file path
     * @param ThemeBuilder $builder A theme builder
     *
     * @return void
     */
    abstract protected function doLoad($path, ThemeBuilder $builder);
}
