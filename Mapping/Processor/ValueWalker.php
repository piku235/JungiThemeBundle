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

/**
 * ValueWalker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class ValueWalker implements WorkerInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        foreach ($registry->getThemeDefinitions() as $theme) {
            if ($theme instanceof StandardThemeDefinition) {
                $theme->setPath($this->resolveValue($theme->getPath(), $registry));
            }

            // Tags
            foreach ($theme->getTags() as $tag) {
                $tag->setArguments($this->resolveValueRecursive($tag->getArguments(), $registry));
            }

            // ThemeInfo
            if (null !== $info = $theme->getInformation()) {
                $info->setProperties($this->resolveValueRecursive($info->getProperties(), $registry));
            }
        }
    }

    /**
     * Resolves a value in a recursive way.
     *
     * @param mixed                            $value    A value
     * @param ThemeDefinitionRegistryInterface $registry A theme definition registry
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     */
    private function resolveValueRecursive($value, ThemeDefinitionRegistryInterface $registry)
    {
        if (is_array($value)) {
            foreach ($value as &$child) {
                $child = $this->resolveValueRecursive($child, $registry);
            }

            return $value;
        }

        return $this->resolveValue($value, $registry);
    }

    /**
     * Resolves a true value.
     *
     * @param string                           $value    A value
     * @param ThemeDefinitionRegistryInterface $registry A theme definition registry
     *
     * @return mixed Should return a passed value if there is no need for resolve
     */
    abstract protected function resolveValue($value, ThemeDefinitionRegistryInterface $registry);
}
