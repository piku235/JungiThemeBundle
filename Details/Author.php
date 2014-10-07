<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Details;

/**
 * Author is the default implementation of the AuthorInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Author implements AuthorInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $website;

    /**
     * Constructor
     *
     * @param string $name    An author name
     * @param string $email   An author email
     * @param string $website An author website (optional)
     */
    public function __construct($name, $email, $website = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->website = $website;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * String representation
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s (%s)', $this->name, $this->email);
    }
}
