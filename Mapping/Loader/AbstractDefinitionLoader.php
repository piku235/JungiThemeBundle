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

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\ProcessorInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * AbstractDefinitionLoader.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AbstractDefinitionLoader implements DefinitionLoaderInterface
{
    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var ThemeDefinitionRegistryInterface
     */
    private $registry;

    /**
     * @var FileLocatorInterface
     */
    protected $locator;

    /**
     * Constructor.
     *
     * @param ProcessorInterface               $processor A processor
     * @param ThemeDefinitionRegistryInterface $registry  A theme definition registry
     * @param FileLocatorInterface             $locator   A file locator
     */
    public function __construct(ProcessorInterface $processor, ThemeDefinitionRegistryInterface $registry, FileLocatorInterface $locator)
    {
        $this->processor = $processor;
        $this->registry = $registry;
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If a file is not readable
     * @throws \RuntimeException         If an exception will be thrown while building themes
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);

        // Loads the file
        if (null === $registry = $this->doLoad($path)) {
            // Abort if encountered null
            return;
        }

        // Build a theme collection from definitions
        try {
            $this->processor->process($registry);

            foreach ($registry->getThemeDefinitions() as $name => $definition) {
                $this->registry->registerThemeDefinition($name, $definition);
            }
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf(
                'A problem occurred while processing themes from the file "%s", see the previous exception for more details.',
                $path
            ), null, $e);
        }
    }

    /**
     * Performs the main load action.
     *
     * @param string $path A file path
     *
     * @return ThemeDefinitionRegistryInterface
     */
    abstract protected function doLoad($path);
}
