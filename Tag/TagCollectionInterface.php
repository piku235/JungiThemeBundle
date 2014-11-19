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
 * TagCollection provides features for flexible operations on theme tags
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface TagCollectionInterface extends \Countable, \Traversable
{
    /**
     * @var string
     */
    const COND_AND = 'and';

    /**
     * @var string
     */
    const COND_OR = 'or';

    /**
     * Returns a tag by tag name
     *
     * @param string $name A tag name
     *
     * @return TagInterface
     *
     * @throws \RuntimeException When there is no tag with a given tag name
     */
    public function get($name);

    /**
     * Checks if a given tag name exists
     *
     * Be careful, because this method ONLY looks for a given tag name
     * and it does not check if the tag is EQUAL to a found tag
     *
     * @param string $name A tag name
     *
     * @return bool
     */
    public function has($name);

    /**
     * The same as method "has" with the difference that it can
     * iterate over given tag names
     *
     * @param array  $names     Tag names
     * @param string $condition A condition (optional)
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given condition is incorrect
     */
    public function hasSet(array $names, $condition = self::COND_AND);

    /**
     * Checks if a given tag or collection of tags exists and if they are EQUAL to the found tags
     *
     * @param TagInterface $tag A tag
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given tag or tags has bad type
     */
    public function contains(TagInterface $tag);

    /**
     * The same as method "contains" with the difference that it can
     * iterate over given tags
     *
     * @param array  $tags      Tags
     * @param string $condition A condition (optional)
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the given condition is incorrect
     */
    public function containsSet(array $tags, $condition = self::COND_AND);
}
