<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Loader;

use Jungi\Bundle\ThemeBundle\Mapping\Loader\LoaderHelper;
use Jungi\Bundle\ThemeBundle\Tag\MobileDevices;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistry;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * LoaderHelper Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class LoaderHelperTest extends TestCase
{
    private $helper;

    protected function setUp()
    {
        $registry = new TagRegistry();
        $registry->register('Jungi\Bundle\ThemeBundle\Tag\MobileDevices');
        $this->helper = new LoaderHelper($registry);
    }

    public function testExistingConst()
    {
        define('JUNGI_TEST', 'test_foo');

        $this->assertEquals(JUNGI_TEST, $this->helper->resolveConstant('JUNGI_TEST'));
    }

    public function testExistingTagConst()
    {
        $this->assertEquals(MobileDevices::MOBILE, $this->helper->resolveConstant('jungi.mobile_devices::MOBILE'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotExistingConst()
    {
        $this->helper->resolveConstant('jungi.mobile_devices::NOT_EXISTENT');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongWrittenConst()
    {
        $this->helper->resolveConstant('foo_boo');
    }
} 