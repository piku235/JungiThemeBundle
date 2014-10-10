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
    protected $homepage;

    /**
     * Constructor
     *
     * @param string $name     An author name
     * @param string $email    An author email
     * @param string $homepage An author homepage (optional)
     *
     * @throws \RuntimeException If the name or the email wasn't provided
     */
    public function __construct($name, $email, $homepage = null)
    {
        if (!$name || !$email) {
            throw new \RuntimeException('You must provide the author name and/or the author email.');
        }

        $this->name = $name;
        $this->email = $email;
        $this->homepage = $homepage;
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
    public function getHomepage()
    {
        return $this->homepage;
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
