<?php

namespace Jungi\Bundle\ThemeBundle\Core\Information;

/**
 * ThemeInfoEssenceBuilder is a builder which helps with creating objects of the ThemeInfoEssence.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoEssenceBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var AuthorInterface[]
     */
    private $authors = array();

    /**
     * Sets a friendly theme name.
     *
     * @param string $name A name
     *
     * @return ThemeInfoEssenceBuilder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets a theme description.
     *
     * @param string $description A description
     *
     * @return ThemeInfoEssenceBuilder
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Adds an author.
     *
     * @param AuthorInterface $author An author
     *
     * @return ThemeInfoEssenceBuilder
     */
    public function addAuthor(AuthorInterface $author)
    {
        $this->authors[] = $author;

        return $this;
    }

    /**
     * Adds authors.
     *
     * @param AuthorInterface[] $authors Authors
     *
     * @return ThemeInfoEssenceBuilder
     */
    public function addAuthors(array $authors)
    {
        foreach ($authors as $author) {
            $this->addAuthor($author);
        }

        return $this;
    }

    /**
     * Returns the all fields of the builder.
     *
     * @return \stdClass
     */
    public function getFields()
    {
        $obj = new \stdClass();
        $obj->name = $this->name;
        $obj->description = $this->description;
        $obj->authors = $this->authors;

        return $obj;
    }

    /**
     * Builds the ThemeInfoEssence instance.
     *
     * @return ThemeInfoEssence
     *
     * @throws \RuntimeException When the name is missing
     */
    public function getThemeInfo()
    {
        if (!$this->name) {
            throw new \RuntimeException(sprintf('You cannot leave the name property empty.'));
        }

        return new ThemeInfoEssence($this);
    }
}
