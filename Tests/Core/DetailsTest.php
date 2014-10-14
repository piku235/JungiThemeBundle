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
use Jungi\Bundle\ThemeBundle\Details\Details;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * Details Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DetailsTest extends TestCase
{
    public function testValidCreation()
    {
        $author = new Author('test_author', 'test_author_email', 'test_author_www');
        $builder = Details::createBuilder();
        $builder
            ->setName('Super Theme')
            ->setVersion('1.0')
            ->setDescription('test')
            ->setLicense('MIT')
            ->addAuthor($author)
        ;

        $details = $builder->getDetails();
        $this->assertEquals('Super Theme', $details->getName());
        $this->assertEquals('1.0', $details->getVersion());
        $this->assertEquals('test', $details->getDescription());
        $this->assertEquals('MIT', $details->getLicense());
        $this->assertEquals(array($author), $details->getAuthors());
    }
}
