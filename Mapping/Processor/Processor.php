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

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagClassRegistryInterface;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Processor.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class Processor implements ProcessorInterface
{
    /**
     * @var WorkerInterface[]
     */
    private $workers;

    /**
     * Constructor.
     *
     * @param TagClassRegistryInterface $tagClassRegistry A tag class registry
     * @param FileLocatorInterface      $locator          A file locator
     * @param array                     $globalParams     A global parameters (optional)
     */
    public function __construct(TagClassRegistryInterface $tagClassRegistry, FileLocatorInterface $locator, array $globalParams = array())
    {
        $this->workers = array(
            new GlobalParametersWorker($globalParams),
            new DelegatingParameterValueReplacer(),
            new ConstantValueReplacer($tagClassRegistry),
            new ThemePathWorker($locator),
            new ThemeInfoValidationWorker(),
            new VirtualThemeWorker(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        foreach ($this->workers as $worker) {
            $worker->process($registry);
        }
    }
}
