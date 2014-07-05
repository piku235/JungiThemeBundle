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
            'jungi.link' => '\Jungi\Bundle\ThemeBundle\Tag\Link',
            'jungi.desktop_devices' => '\Jungi\Bundle\ThemeBundle\Tag\DesktopDevices'
        );
        $provider = new TagProvider($tags['jungi.desktop_devices']);
        $registry = new TagRegistry();
        $registry->register(array_slice($tags, 0, 2, true));
        $registry->register($provider);

        foreach ($tags as $type => $class) {
            $this->assertEquals($class, $registry->getTagClass($type));
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
        $registry->register('Jungi\Bundle\ThemeBundle\Tag\MobileDevices');
        $registry->getTagClass('jungi.not_existent');
    }

    /**
     * Tests on invalid tag class
     *
     * @expectedException \RuntimeException
     */
    public function testOnNonExistentClass()
    {
        $registry = new TagRegistry();
        $registry->register('Jungi\Bundle\ThemeBundle\Tag\NotExistentTag');
    }

    /**
     * Tests on invalid tag class
     *
     * @expectedException \InvalidArgumentException
     */
    public function testOnInvalidTagClass()
    {
        $registry = new TagRegistry();
        $registry->register('Jungi\Bundle\ThemeBundle\Core\Theme');
    }
} 