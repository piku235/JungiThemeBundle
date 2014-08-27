<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag\Registry;

/**
 * TagProvider is only responsible for easily registering tag classes by symfony services
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class TagProvider
{
    /**
     * @var array
     */
    private $classes;

    /**
     * Constructor
     *
     * @param string|array $class A tag class or tag classes
     */
    public function __construct($class)
    {
        $this->classes = (array) $class;
    }

    /**
     * Dumps the registered tag classes
     *
     * @return array
     */
    public function dump()
    {
        return $this->classes;
    }
}
