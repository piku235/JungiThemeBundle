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

use Jungi\Bundle\ThemeBundle\Core\Details;
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
     * @param string $value A value with a constant reference
     *
     * @return mixed
     *
     * @throws \RuntimeException When a constant is not exist
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

        throw new \RuntimeException(sprintf('The constant "%s" is not exist.', $value));
    }

    /**
     * Creates a Details instance
     *
     * Format of the $data variable should looks like following:
     * array(
     *  'author.name' => 'foo',
     *  'author.email' => 'foo@boo.com',
     *  'name' => 'foo'
     *  // ...
     *  key => value
     * )
     *
     * @param array $data Data
     *
     * @return Details
     *
     * @throws \InvalidArgumentException If in a given array is placed invalid key
     * @throws \LogicException If there is missing required argument
     */
    public function createDetails(array $data)
    {
        $validKeys = array(
            'author.name',
            'author.www',
            'author.email',
            'name',
            'description',
            'version',
            'license'
        );
        array_walk($data, function ($val, $key) use ($validKeys) {
            if (!in_array($key, $validKeys)) {
                throw new \InvalidArgumentException(sprintf('The key "%s" for Details instance is invalid.', $key));
            }
        });
        $property = function ($name) use ($data) {
            return isset($data[$name]) ? $data[$name] : null;
        };

        if (!$property('name') || !$property('version')) {
            throw new \LogicException('You must provide "name" and/or "version" argument.');
        }

        return new Details(
            $property('name'),
            $property('version'),
            $property('description'),
            $property('license'),
            $property('author.name'),
            $property('author.email'),
            $property('author.www')
        );
    }
}