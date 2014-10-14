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

use Jungi\Bundle\ThemeBundle\Details\Author;
use Jungi\Bundle\ThemeBundle\Details\DetailsBuilder;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * DetailsBuilderTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DetailsBuilderTest extends TestCase
{
    public function testValidCreation()
    {
        $name = 'Super Theme';
        $version = '1.0';
        $description = 'test';
        $license = 'MIT';
        $author = new Author('test_author', 'test_author_email', 'test_author_www');

        $builder = new DetailsBuilder();
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
        $builder = new DetailsBuilder();
        $builder->setDescription('test');

        $builder->getDetails();
    }
}
