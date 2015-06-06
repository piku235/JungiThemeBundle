<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Dumper;

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * DumperInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface DumperInterface
{
    /**
     * Dumps a given theme definition registry.
     *
     * @param ThemeDefinitionRegistryInterface $registry
     *
     * @return string
     */
    public function dump(ThemeDefinitionRegistryInterface $registry);
}
