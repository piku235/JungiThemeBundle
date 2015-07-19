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

use Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * GlobalParametersWorker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class GlobalParametersWorker implements WorkerInterface
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param array $parameters Global parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        if ($this->parameters && $registry instanceof ParametricThemeDefinitionRegistryInterface) {
            $registry->setParameters($this->parameters);
        }
    }
}
