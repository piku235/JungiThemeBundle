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
 * All properties can be only set by constructor
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
    protected $authorEmail;

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
     * @var string
     */
    protected $thumbnail;

    /**
     * Constructor
     *
     * @param array $params Parameters
     *  The acceptable parameters:
     *  array(
     *      'name' => value,
     *      'version' => value,
     *      'description' => value,
     *      'license' => value,
     *      'thumbnail' => value,
     *      'author.name' => value,
     *      'author.site' => value,
     *      'author.email' => value,
     *  )
     * @throws \InvalidArgumentException When some of given parameters can not be handled
     * @throws \InvalidArgumentException If one of required parameters was not passed
     */
    public function __construct(array $parameters)
    {
        $validKeys = array(
            'name',
            'description',
            'version',
            'license',
            'thumbnail',
            'author.name',
            'author.site',
            'author.email'
        );
        $property = function ($name) use ($parameters) {
            return isset($parameters[$name]) ? $parameters[$name] : null;
        };
        if ($wrong = array_diff(array_keys($parameters), $validKeys)) {
            throw new \InvalidArgumentException(sprintf('The given parameters "%s" can not be handled.', implode(', ', $wrong)));
        } else if (!$property('name') || !$property('version')) {
            throw new \InvalidArgumentException('You must provide "name" and "version" argument.');
        }

        $this->name = $property('name');
        $this->version = $property('version');
        $this->description = $property('description');
        $this->license = $property('license');
        $this->thumbnail = $property('thumbnail');
        $this->author = $property('author.name');
        $this->authorEmail = $property('author.email');
        $this->authorSite = $property('author.site');
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
     * Returns the author email address
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
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
     * Returns the location of a thumbnail
     *
     * @return string|null
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Represents the details object
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s, %s (%s)', $this->name, $this->author ? $this->author : 'missing', $this->authorEmail ? $this->authorEmail : 'missing');
    }
}