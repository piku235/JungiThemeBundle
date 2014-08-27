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

use Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactoryInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;

/**
 * FileLoader is a basic class for loading themes from mapping documents to a ThemeManagerInterface instance
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class FileLoader
{
    /**
     * @var ThemeManagerInterface
     */
    protected $themeManager;

    /**
     * @var FileLocatorInterface
     */
    protected $locator;

    /**
     * @var TagFactoryInterface
     */
    protected $tagFactory;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface $themeManager A theme manager
     * @param FileLocatorInterface  $locator      A file locator
     * @param TagFactoryInterface   $factory      A tag factory
     */
    public function __construct(ThemeManagerInterface $themeManager, FileLocatorInterface $locator, TagFactoryInterface $factory)
    {
        $this->locator = $locator;
        $this->themeManager = $themeManager;
        $this->tagFactory = $factory;
    }

    /**
     * Loads themes from a given theme mapping file
     *
     * @param string $file A file
     *
     * @return void
     *
     * @throws \DomainException When the file is not supported by FileLoader
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
