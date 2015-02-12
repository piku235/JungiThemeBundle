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

use Symfony\Component\Config\FileLocatorInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;

/**
 * FileLoader is a basic class for loading themes from mapping documents to a ThemeRegistryInterface instance
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class FileLoader
{
    /**
     * @var FileLocatorInterface
     */
    protected $locator;

    /**
     * @var ThemeRegistryInterface
     */
    protected $themeRegistry;

    /**
     * Constructor
     *
     * @param ThemeRegistryInterface $themeReg A theme registry
     * @param FileLocatorInterface $locator A file locator
     */
    public function __construct(ThemeRegistryInterface $themeReg, FileLocatorInterface $locator)
    {
        $this->themeRegistry = $themeReg;
        $this->locator = $locator;
    }

    /**
     * Loads themes from a given theme mapping file
     *
     * @param string $file A file
     *
     * @return void
     */
    abstract public function load($file);

    /**
     * Checks if the this loader can handle a given file
     *
     * @param string $file A file
     *
     * @return bool
     */
    abstract public function supports($file);
}
