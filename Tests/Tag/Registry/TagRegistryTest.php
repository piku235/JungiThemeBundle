<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Tag\Registry;

use Jungi\Bundle\ThemeBundle\Tag\Registry\TagProvider;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistry;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * TagRegistryTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagRegistryTest extends TestCase
{
    /**
     * Tests on valid tag classes
     */
    public function testOnValidTagClasses()
    {
        $tags = array(
            'jungi.mobile_devices' => '\Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'jungi.desktop_devices' => '\Jungi\Bundle\ThemeBundle\Tag\DesktopDevices',
            'jungi.fake' => '\Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\FakeTag',
        );
        $provider = new TagProvider($tags['jungi.fake']);
        $registry = new TagRegistry();
        $registry->registerTag(array_slice($tags, 0, 2, true));
        $registry->registerTag($provider);

        $this->assertEquals($tags, $registry->getTags());
        foreach ($tags as $type => $class) {
            $this->assertEquals($class, $registry->getTag($type));
        }
    }

    /**
     * Tests on invalid tag class
     *
     * @expectedException \InvalidArgumentException
     */
    public function testOnNonExistentTagType()
    {
        $registry = new TagRegistry();
        $registry->registerTag('Jungi\Bundle\ThemeBundle\Tag\MobileDevices');
        $registry->getTag('jungi.not_existent');
    }

    /**
     * Tests on invalid tag class
     *
     * @expectedException \RuntimeException
     */
    public function testOnNonExistentClass()
    {
        $registry = new TagRegistry();
        $registry->registerTag('Jungi\Bundle\ThemeBundle\Tag\NotExistentTag');
    }

    /**
     * Tests on invalid tag class
     *
     * @expectedException \InvalidArgumentException
     */
    public function testOnInvalidTagClass()
    {
        $registry = new TagRegistry();
        $registry->registerTag('Jungi\Bundle\ThemeBundle\Core\Theme');
    }
}
