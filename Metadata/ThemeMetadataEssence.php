<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Metadata;

/**
 * The class is a simple implementation of the ThemeMetadata and was built
 * on the basis of the Essence design pattern
 *
 * To set properties of the class you must use the ThemeMetadataBuilder
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeMetadataEssence extends ThemeMetadata
{
    /**
     * Creates a new builder instance
     *
     * @return ThemeMetadataBuilder
     */
    public static function createBuilder()
    {
        return new ThemeMetadataBuilder();
    }

    /**
     * Constructor
     *
     * @param ThemeMetadataBuilder $builder The Metadata builder
     */
    public function __construct(ThemeMetadataBuilder $builder)
    {
        $fields = $builder->getFields();

        $this->name = $fields->name;
        $this->version = $fields->version;
        $this->description = $fields->description;
        $this->license = $fields->license;
        $this->authors = $fields->authors;
    }
}
