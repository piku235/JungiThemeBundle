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
 * the Essence design pattern
 *
 * To set properties of the class you must use the ThemeInfoBuilder
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoEssence extends ThemeInfo
{
    /**
     * Creates a new builder instance
     *
     * @return ThemeInfoBuilder
     */
    public static function createBuilder()
    {
        return new ThemeInfoBuilder();
    }

    /**
     * Constructor
     *
     * @param ThemeInfoBuilder $builder The Information builder
     */
    public function __construct(ThemeInfoBuilder $builder)
    {
        $fields = $builder->getFields();

        $this->name = $fields->name;
        $this->version = $fields->version;
        $this->description = $fields->description;
        $this->license = $fields->license;
        $this->authors = $fields->authors;
    }
}
