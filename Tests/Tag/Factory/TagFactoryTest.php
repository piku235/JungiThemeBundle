<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Tag\Factory;

use Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactory;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistry;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * TagFactory Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagFactoryTest extends TestCase
{
    /**
     * @var TagFactory
     */
    private $factory;

    /**
     * Set up
     */
    protected function setUp()
    {
        $registry = new TagRegistry();
        $registry->register(array(
            'Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'Jungi\Bundle\ThemeBundle\Tag\VirtualTheme',
        ));
        $this->factory = new TagFactory($registry);
    }

    /**
     * @dataProvider getValidTagReferences
     */
    public function testOnValidTagTypes($type, $arguments, $validTag)
    {
        $this->assertEquals($validTag, $this->factory->create($type, $arguments));
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getValidTagReferences()
    {
        return array(
            array(Tag\MobileDevices::getName(), array(), new Tag\MobileDevices()),
            array(Tag\MobileDevices::getName(), array('iOS', Tag\MobileDevices::TABLET), new Tag\MobileDevices('iOS', Tag\MobileDevices::TABLET)),
            array(Tag\VirtualTheme::getName(), 'footheme', new Tag\VirtualTheme('footheme')),
        );
    }
}
