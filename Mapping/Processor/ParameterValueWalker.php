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

use Jungi\Bundle\ThemeBundle\Mapping\ContainerInterface;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * ParameterValueWalker processes parameters contained in a theme definition registry.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParameterValueWalker extends ValueWalker
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        if (!$registry instanceof ContainerInterface || !$registry->getParameters()) {
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
     * @throws \RuntimeException When the given parameter does not exist
     */
    protected function resolveValue($value, ThemeDefinitionRegistryInterface $registry)
    {
        /** @var ContainerInterface $registry */

        if (!is_string($value) || !preg_match('/^%([^\s%]+)%$/', $value, $matches)) {
            return $value;
        }

        $paramName = $matches[1];
        if (!$registry->hasParameter($paramName)) {
            throw new \RuntimeException(sprintf('The parameter "%s" can not be found.', $paramName));
        }

        return $registry->getParameter($paramName);
    }
}
