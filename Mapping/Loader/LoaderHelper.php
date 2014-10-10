<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Loader;

use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistryInterface;

/**
 * LoaderHelper additionally provides useful methods for theme mapping loaders
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class LoaderHelper
{
    /**
     * @var TagRegistryInterface
     */
    protected $tagRegistry;

    /**
     * Constructor
     *
     * @param TagRegistryInterface $registry A tag registry
     */
    public function __construct(TagRegistryInterface $registry)
    {
        $this->tagRegistry = $registry;
    }

    /**
     * Resolves the value of a given constant
     *
     * @param string $value A value with the constant reference
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException When the constant is wrong or not found
     */
    public function resolveConstant($value)
    {
        if (defined($value)) {
            return constant($value);
        }

        // Is the constant located in a class or a tag type?
        if (false !== $pos = strrpos($value, '::')) {
            // A class or a tag type
            $location = substr($value, 0, $pos);
            if (false !== strpos($location, '.')) { // Is the location of a tag type?
                $location = $this->tagRegistry->getTagClass($location);
            }

            $const = $location . substr($value, $pos);
            if (defined($const)) {
                return constant($const);
            }
        }

        throw new \InvalidArgumentException(sprintf('The constant "%s" is wrong or it can not be found.', $value));
    }
}
