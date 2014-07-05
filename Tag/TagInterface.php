<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag;

/**
 * Tag takes the information role and can be used for searching and grouping themes
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface TagInterface
{
    /**
     * Checks if a given tag is equal
     *
     * @param  TagInterface $tag A tag
     * @return bool
     */
    public function isEqual(TagInterface $tag);

    /**
     * Gets the tag name
     *
     * The returned value should be in the following format: "vendor.tag_type"
     * for eg. "jungi.mobile_devices". This format prevents from replacing
     * tags by different vendors
     *
     * @return string
     */
    public static function getName();
}