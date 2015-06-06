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

/**
 * Processor.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Processor implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $workers = array();

    /**
     * Adds a processor worker.
     *
     * @param WorkerInterface $worker A worker
     */
    public function addWorker(WorkerInterface $worker)
    {
        $this->workers[] = $worker;
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
