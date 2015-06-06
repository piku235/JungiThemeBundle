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
 * ProcessorInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * Processes a given theme definition registry.
     *
     * @param ThemeDefinitionRegistryInterface $registry A registry
     */
    public function process(ThemeDefinitionRegistryInterface $registry);
}
