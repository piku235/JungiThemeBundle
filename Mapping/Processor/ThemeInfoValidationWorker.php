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

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * ThemeInfoValidationWorker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoValidationWorker extends AbstractThemeWorker
{
    /**
     * {@inheritdoc}
     */
    protected function processTheme($name, ThemeDefinition $definition, ThemeDefinitionRegistryInterface $registry)
    {
        $info = $definition->getInfo();
        if (null === $info || !$info->hasProperty('authors')) {
            return;
        }

        if (!$info->hasProperty('name')) {
            throw new \InvalidArgumentException(sprintf(
                'A theme name can not be blank, encountered in the theme "%s".',
                $name
            ));
        }

        $authors = $info->getProperty('authors');
        if (!is_array($authors)) {
            throw new \InvalidArgumentException(sprintf(
                'The authors property should be an array, encountered in the theme "%s".',
                $name
            ));
        }

        foreach ($authors as $author) {
            if (!is_array($author)) {
                throw new \InvalidArgumentException(sprintf(
                    'An author of the authors property should be an array, encountered in the theme "%s".',
                    $name
                ));
            }

            $required = array('name', 'email');
            if ($missing = array_diff(array_values($required), array_keys($author))) {
                throw new \InvalidArgumentException(sprintf(
                    'There are missing property(ies): %s of the author property in the theme "%s."',
                    implode(', ', $missing),
                    $name
                ));
            }
        }
    }
}
