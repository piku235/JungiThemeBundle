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
     *
     * @throws \RuntimeException If the file is not local
     * @throws \DomainException  If the file is not supported
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);

        if (!stream_is_local($file)) {
            throw new \RuntimeException(sprintf('The "%s" file is not local.', $file));
        } elseif (!$this->supports($file)) {
            throw new \DomainException(sprintf('The given file "%s" is not supported.', $file));
        }

        // Vars available for mapping file
        $manager = $this->themeManager;
        $locator = $this->locator;
        $tagFactory = $this->tagFactory;

        // Include
        include $path;
    }
}
