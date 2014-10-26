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
 * ThemeInfo is used to describe a theme and instances of this interface can return e.g.
 * a theme name or a theme version.
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
    protected $version;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $license;

    /**
     * Returns the friendly theme name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the authors
     *
     * @return AuthorInterface[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Returns the version
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Returns the description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the license type
     *
     * @return string|null
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Represents the info object
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s v. %s', $this->name, $this->version ?: 'unknown');
    }
}
