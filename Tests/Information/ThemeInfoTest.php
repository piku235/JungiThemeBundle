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

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ThemeInfo Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfoTest extends TestCase
{
    public function testValidCreation()
    {
        $author = new Author('test_author', 'test_author_email', 'test_author_www');
        $builder = ThemeInfoEssence::createBuilder();
        $builder
            ->setName('Super Theme')
            ->setVersion('1.0')
            ->setDescription('test')
            ->setLicense('MIT')
            ->addAuthor($author)
        ;

        $info = $builder->getInformation();
        $this->assertEquals('Super Theme', $info->getName());
        $this->assertEquals('1.0', $info->getVersion());
        $this->assertEquals('test', $info->getDescription());
        $this->assertEquals('MIT', $info->getLicense());
        $this->assertEquals(array($author), $info->getAuthors());
    }
}
