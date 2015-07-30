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

use Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\ParametricThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * The class processes parameters contained in a theme definition registry.
 *
 * It delegates resolving a value to a ParameterBag instance.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DelegatingParameterValueReplacer extends ValueReplacer
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        if (!$registry instanceof ParametricThemeDefinitionRegistry || !$registry->getParameters()) {
            return;
        }

        $registry->getParameterBag()->resolve();
        parent::process($registry);
    }

    /**
     * Resolves a parameter value if present.
     *
     * @param mixed                            $value    A value
     * @param ThemeDefinitionRegistryInterface $registry A theme definition registry
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException When the given parameter does not exist
     */
    protected function resolveValue($value, ThemeDefinitionRegistryInterface $registry)
    {
        /** @var ParametricThemeDefinitionRegistry $registry */
        return $registry->getParameterBag()->resolveValue($value);
    }
}
