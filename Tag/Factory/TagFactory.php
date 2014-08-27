<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag\Factory;

use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistryInterface;

/**
 * TagFactory is a simple implementation of the TagFactoryInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagFactory implements TagFactoryInterface
{
    /**
     * @var \ReflectionClass[]
     */
    private $cache;

    /**
     * @var TagRegistryInterface
     */
    protected $registry;

    /**
     * Constructor
     *
     * @param TagRegistryInterface $registry A tag registry
     */
    public function __construct(TagRegistryInterface $registry)
    {
        $this->registry = $registry;
        $this->cache = array();
    }

    /**
     * Creates the tag by a given tag name
     *
     * @param string       $name      A tag name
     * @param string|array $arguments Arguments or an argument for a tag (optional)
     *
     * @return string
     */
    public function create($type, $arguments = null)
    {
        if (!isset($this->cache[$type])) {
            $this->cache[$type] = new \ReflectionClass($this->registry->getTagClass($type));
        }

        $reflection = $this->cache[$type];
        if ($arguments) {
            if (!is_array($arguments)) {
                return $reflection->newInstance($arguments);
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return $reflection->newInstance();
    }
}
