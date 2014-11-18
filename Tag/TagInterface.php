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
 * TagInterface is a basic interface for theme tags.
 *
 * Theme tags are very straightforward thanks to the small API. Tags have a great functionality
 * that allows us for doing many things. Without them the DeviceThemeFilter class would not work
 * properly.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface TagInterface
{
    /**
     * Checks if a given tag is equal
     *
     * @param TagInterface $tag A tag
     *
     * @return bool
     */
    public function isEqual(TagInterface $tag);

    /**
     * Gets the tag name
     *
     * The returned name should be in the following notation: "vendor.tag_type" e.g. "jungi.mobile_devices".
     * This notation prevents from replacing tags by different vendors
     *
     * @return string
     */
    public static function getName();
}
