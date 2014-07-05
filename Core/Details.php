<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

/**
 * Details is a simple implementation of the DetailsInterface
 *
 * All properties can be only set by the constructor
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Details implements DetailsInterface
{
    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $authorMail;

    /**
     * @var string
     */
    protected $authorSite;

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
     * Constructor
     *
     * @param string $name A friendly theme name
     * @param string $version A version
     * @param string $description A description
     * @param string $license A license type
     * @param string $author An author name
     * @param string $authorMail An author mail
     * @param string $authorSite An author site (optional)
     */
    public function __construct($name, $version, $description = null, $license = null, $author = null, $authorMail = null, $authorSite = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->description = $description;
        $this->license = $license;
        $this->author = $author;
        $this->authorMail = $authorMail;
        $this->authorSite = $authorSite;
    }

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
     * Returns the author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Returns the author mail
     *
     * @return string
     */
    public function getAuthorMail()
    {
        return $this->authorMail;
    }

    /**
     * Returns the author site
     *
     * @return string
     */
    public function getAuthorSite()
    {
        return $this->authorSite;
    }

    /**
     * Returns the version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Returns the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the type of license
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Represents the details object
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s, %s (%s)', $this->name, $this->author ? $this->author : 'missing', $this->authorMail ? $this->authorMail : 'missing');
    }
}