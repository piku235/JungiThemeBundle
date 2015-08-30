<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping;

use Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfo as BaseThemeInfo;

/**
 * ThemeInfoImporter.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoImporter
{
    /**
     * @param BaseThemeInfo $info A theme info
     *
     * @return ThemeInfo
     */
    public static function import(BaseThemeInfo $info)
    {
        $definition = new ThemeInfo();
        $definition->setProperty('name', $info->getName());
        if (null !== $info->getDescription()) {
            $definition->setProperty('description', $info->getDescription());
        }

        if ($info->getAuthors()) {
            $authors = array();
            foreach ($info->getAuthors() as $author) {
                $properties = array(
                    'name' => $author->getName(),
                    'email' => $author->getEmail(),
                );
                if (null !== $author->getHomepage()) {
                    $properties['homepage'] = $author->getHomepage();
                }

                $authors[] = $properties;
            }

            $definition->setProperty('authors', $authors);
        }

        return $definition;
    }
}
