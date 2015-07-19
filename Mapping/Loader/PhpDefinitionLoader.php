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

use Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * PhpFileLoader is responsible for creating theme instances from a php mapping file.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpDefinitionLoader extends AbstractDefinitionLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($file, $type = null)
    {
        return 'php' == pathinfo($file, PATHINFO_EXTENSION) || 'php' === $type;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \UnexpectedValueException When a included file returns a wrong collection
     */
    protected function doLoad($file)
    {
        $path = $this->locator->locate($file);

        // Vars available for mapping file
        $loader = $this;
        $registry = new ParametricThemeDefinitionRegistry();

        // Require
        require $path;

        if (!$registry instanceof ThemeDefinitionRegistryInterface) {
            throw new \UnexpectedValueException(sprintf(
                'Expected the \$registry to be of a ThemeDefinitionRegistryInterface instance in the file "%s".',
                $path
            ));
        }

        return $registry;
    }
}
