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
 * ParameterValueReplacer processes parameters contained in a theme definition registry.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParameterValueReplacer extends ValueReplacer
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        if (!$registry instanceof ParametricThemeDefinitionRegistryInterface || !$registry->getParameters()) {
            return;
        }

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
        /* @var ParametricThemeDefinitionRegistryInterface $registry */

        if (!is_string($value) || !preg_match('/^%([^\s%]+)%$/', $value, $matches)) {
            return $value;
        }

        $paramName = $matches[1];
        if (!$registry->hasParameter($paramName)) {
            throw new \InvalidArgumentException(sprintf('The parameter "%s" can not be found.', $paramName));
        }

        return $registry->getParameter($paramName);
    }
}
