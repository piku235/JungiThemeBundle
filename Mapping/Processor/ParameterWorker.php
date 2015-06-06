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
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * ParameterWorker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParameterWorker implements WorkerInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        if (!$registry instanceof ContainerInterface || !$registry->getParameters()) {
            return;
        }

        foreach ($registry->getThemeDefinitions() as $theme) {
            if ($theme instanceof StandardThemeDefinition) {
                $theme->setPath($this->resolveParameter($theme->getPath(), $registry));
            }

            // Tags
            foreach ($theme->getTags() as $tag) {
                $args = $tag->getArguments();
                foreach ($args as &$arg) {
                    $arg = $this->resolveParameter($arg, $registry);
                }
                $tag->setArguments($args);
            }

            // ThemeInfo
            if (null !== $info = $theme->getInformation()) {
                $info->setProperties($this->resolveParameterRecursive($info->getProperties(), $registry));
            }
        }
    }

    /**
     * Resolves a parameter value in a recursive way if present.
     *
     * @param mixed              $value     A value
     * @param ContainerInterface $container A container
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     */
    private function resolveParameterRecursive($value, ContainerInterface $container)
    {
        if (is_array($value)) {
            foreach ($value as &$child) {
                $child = $this->resolveParameterRecursive($child, $container);
            }

            return $value;
        }

        return $this->resolveParameter($value, $container);
    }

    /**
     * Resolves a parameter value if present.
     *
     * @param string             $value     A value
     * @param ContainerInterface $container A container
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     */
    private function resolveParameter($value, ContainerInterface $container)
    {
        if (!preg_match('/^%([^\s%]+)%$/', $value, $matches)) {
            return $value;
        }

        $paramName = $matches[1];
        if (!$container->hasParameter($paramName)) {
            throw new \RuntimeException(sprintf('The parameter "%s" can not be found.', $paramName));
        }

        return $container->getParameter($paramName);
    }
}
