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

use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactory;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistry;

/**
 * AbstractFileLoader Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AbstractFileLoaderTest extends TestCase
{
    /**
     * @var ThemeManager
     */
    protected $manager;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    /**
     * @var TagFactory
     */
    protected $tagFactory;

    /**
     * @var TagRegistry
     */
    protected $tagRegistry;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manager = new ThemeManager();
        $this->tagRegistry = new TagRegistry();
        $this->tagRegistry->register(array(
            'Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'Jungi\Bundle\ThemeBundle\Tag\DesktopDevices',
            'Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Own',
        ));
        $this->tagFactory = new TagFactory($this->tagRegistry);
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel
            ->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(__DIR__ . '/Fixtures/fake_bundle'))
        ;
    }
}
