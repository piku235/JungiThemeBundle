<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Information;

/**
 * The class is a simple implementation of the ThemeInfo and was built on the basis of
 * the Essence design pattern.
 *
 * To set properties of the class you must use the ThemeInfoEssenceBuilder
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoEssence extends ThemeInfo
{
    /**
     * Creates a new builder instance.
     *
     * @return ThemeInfoEssenceBuilder
     */
    public static function createBuilder()
    {
        return new ThemeInfoEssenceBuilder();
    }

    /**
     * Constructor.
     *
     * @param ThemeInfoEssenceBuilder $builder The Information builder
     */
    public function __construct(ThemeInfoEssenceBuilder $builder)
    {
        $fields = $builder->getFields();

        $this->name = $fields->name;
        $this->description = $fields->description;
        $this->authors = $fields->authors;
    }
}
