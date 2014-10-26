<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Core;

use Jungi\Bundle\ThemeBundle\Metadata\Author;
use Jungi\Bundle\ThemeBundle\Metadata\ThemeMetadataBuilder;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ThemeMetadataBuilderTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeMetadataBuilderTest extends TestCase
{
    public function testValidCreation()
    {
        $name = 'Super Theme';
        $version = '1.0';
        $description = 'test';
        $license = 'MIT';
        $author = new Author('test_author', 'test_author_email', 'test_author_www');

        $builder = new ThemeMetadataBuilder();
        $builder
            ->setName($name)
            ->setVersion($version)
            ->setDescription($description)
            ->setLicense($license)
            ->addAuthor($author)
        ;

        $fields = $builder->getFields();
        $this->assertEquals($name, $fields->name);
        $this->assertEquals($version, $fields->version);
        $this->assertEquals($description, $fields->description);
        $this->assertEquals($license, $fields->license);
        $this->assertEquals(array($author), $fields->authors);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testOnMissingParameters()
    {
        $builder = new ThemeMetadataBuilder();
        $builder->setDescription('test');

        $builder->getMetadata();
    }
}
