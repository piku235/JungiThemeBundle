<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * ThemePathWorker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemePathWorker implements WorkerInterface
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
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        foreach ($registry->getThemeDefinitions() as $theme) {
            if ($theme instanceof StandardThemeDefinition) {
                $theme->setPath($this->locator->locate($theme->getPath()));
            }
        }
    }
}
