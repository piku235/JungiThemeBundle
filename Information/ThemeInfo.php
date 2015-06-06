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
 * The ThemeInfo is a kind of the interface which is used to describe a theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class ThemeInfo
{
    /**
     * @var AuthorInterface[]
     */
    protected $authors = array();

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $name;

    /**
     * Returns the friendly theme name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the authors.
     *
     * @return AuthorInterface[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Returns the description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Represents the info object.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
