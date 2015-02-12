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
 * PhpFileLoader is responsible for creating theme instances from a php mapping file
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpFileLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return 'php' == pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * {@inheritdoc}
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);

        // Vars available for mapping file
        $locator = $this->locator;
        $registry = $this->themeRegistry;

        // Include
        include $path;
    }
}
