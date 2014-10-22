<?php

namespace Jungi\Bundle\ThemeBundle\Details;

/**
 * DetailsBuilder is a builder which helps with creating the Details instance
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DetailsBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $license;

    /**
     * @var string
     */
    private $description;

    /**
     * @var AuthorInterface[]
     */
    private $authors = array();

    /**
     * Sets a friendly theme name
     *
     * @param string $name A name
     *
     * @return DetailsBuilder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets a theme license
     *
     * @param string $license A theme license
     *
     * @return DetailsBuilder
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Sets a theme description
     *
     * @param string $description A description
     *
     * @return DetailsBuilder
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets a theme version
     *
     * @param string $version A version
     *
     * @return DetailsBuilder
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Adds an author
     *
     * @param AuthorInterface $author An author
     *
     * @return DetailsBuilder
     */
    public function addAuthor(AuthorInterface $author)
    {
        $this->authors[] = $author;

        return $this;
    }

    /**
     * Adds authors
     *
     * @param AuthorInterface[] $authors Authors
     *
     * @return DetailsBuilder
     */
    public function addAuthors(array $authors)
    {
        foreach ($authors as $author) {
            $this->addAuthor($author);
        }

        return $this;
    }

    /**
     * Returns the all fields of the builder
     *
     * @return \stdClass
     */
    public function getFields()
    {
        $obj = new \stdClass();
        $obj->name = $this->name;
        $obj->version = $this->version;
        $obj->license = $this->license;
        $obj->description = $this->description;
        $obj->authors = $this->authors;

        return $obj;
    }

    /**
     * Builds the Details instance
     *
     * @return Details
     *
     * @throws \RuntimeException When the name is missing
     */
    public function getDetails()
    {
        if (!$this->name) {
            throw new \RuntimeException('You must set the name of theme to create new "Details" instance.');
        }

        return new Details($this);
    }
}
