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

use Jungi\Bundle\ThemeBundle\Core\Details;
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
        $params = array(
            'name' => 'Super Theme',
            'version' => '1.0',
            'description' => 'test',
            'license' => 'MIT',
            'thumbnail' => 'test_thumbnail',
            'author.name' => 'test_author',
            'author.site' => 'test_author_www',
            'author.email' => 'test_author_email'
        );
        $details = new Details($params);

        $this->assertEquals($details->getName(), $params['name']);
        $this->assertEquals($details->getVersion(), $params['version']);
        $this->assertEquals($details->getDescription(), $params['description']);
        $this->assertEquals($details->getLicense(), $params['license']);
        $this->assertEquals($details->getThumbnail(), $params['thumbnail']);
        $this->assertEquals($details->getAuthor(), $params['author.name']);
        $this->assertEquals($details->getAuthorSite(), $params['author.site']);
        $this->assertEquals($details->getAuthorEmail(), $params['author.email']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreationOnMissingRequiredParams()
    {
        $details = new Details(array(
            'name' => 'Super Theme',
            'author.email' => 'test_author_email'
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreationOnInvalidParams()
    {
        $details = new Details(array(
            'not_existing' => 'test'
        ));
    }
}
