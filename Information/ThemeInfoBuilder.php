<?php

namespace Jungi\Bundle\ThemeBundle\Information;

/**
 * ThemeInfoBuilder is a builder which helps with creating the ThemeInfoEssence instance
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoBuilder
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
     * @return ThemeInfoBuilder
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
     * @return ThemeInfoBuilder
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
     * @return ThemeInfoBuilder
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
     * @return ThemeInfoBuilder
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
     * @return ThemeInfoBuilder
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
     * @return ThemeInfoBuilder
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
     * Builds the ThemeInfoEssence instance
     *
     * @return ThemeInfoEssence
     *
     * @throws \RuntimeException When the name is missing
     */
    public function getThemeInfo()
    {
        if (!$this->name) {
            throw new \RuntimeException('You must set the name of theme to create a new "ThemeInfoEssence" instance.');
        }

        return new ThemeInfoEssence($this);
    }
}
