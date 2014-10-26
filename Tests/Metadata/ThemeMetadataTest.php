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
use Jungi\Bundle\ThemeBundle\Metadata\ThemeMetadataEssence;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * Metadata Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeMetadataTest extends TestCase
{
    public function testValidCreation()
    {
        $author = new Author('test_author', 'test_author_email', 'test_author_www');
        $builder = ThemeMetadataEssence::createBuilder();
        $builder
            ->setName('Super Theme')
            ->setVersion('1.0')
            ->setDescription('test')
            ->setLicense('MIT')
            ->addAuthor($author)
        ;

        $metadata = $builder->getMetadata();
        $this->assertEquals('Super Theme', $metadata->getName());
        $this->assertEquals('1.0', $metadata->getVersion());
        $this->assertEquals('test', $metadata->getDescription());
        $this->assertEquals('MIT', $metadata->getLicense());
        $this->assertEquals(array($author), $metadata->getAuthors());
    }
}
