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
 * Details is a simple implementation of the DetailsInterface
 *
 * All properties of the class can be only set by the DetailsBuilder
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Details implements DetailsInterface
{
    /**
     * @var AuthorInterface[]
     */
    private $authors;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $license;

    /**
     * Creates a new builder instance
     *
     * @return DetailsBuilder
     */
    public static function createBuilder()
    {
        return new DetailsBuilder();
    }

    /**
     * Constructor
     *
     * @param DetailsBuilder $builder The Details builder
     */
    public function __construct(DetailsBuilder $builder)
    {
        $fields = $builder->getFields();

        $this->name = $fields->name;
        $this->version = $fields->version;
        $this->description = $fields->description;
        $this->license = $fields->license;
        $this->authors = $fields->authors;
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
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
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
        return sprintf('%s v. %s', $this->name, $this->version ?: 'unknown');
    }
}
