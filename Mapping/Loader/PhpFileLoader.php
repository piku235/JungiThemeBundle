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
use Symfony\Component\Config\FileLocatorInterface;

/**
 * PhpFileLoader is responsible for creating theme instances from a php mapping file.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpFileLoader implements LoaderInterface
{
    /**
     * @var FileLocatorInterface
     */
    private $locator;

    /**
     * Constructor.
     *
     * @param FileLocatorInterface $locator A file locator
     */
    public function __construct(FileLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

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
     * @throws \UnexpectedValueException When a included file returns a wrong collection
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);

        // Vars available for mapping file
        $locator = $this->locator;

        // Include
        $collection = include $path;
        if (!$collection instanceof ThemeCollection) {
            throw new \UnexpectedValueException(sprintf(
                'Expected to receive a ThemeCollection instance from the file "%s".',
                $path
            ));
        }

        return $collection;
    }
}
