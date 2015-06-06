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

use Jungi\Bundle\ThemeBundle\Core\ThemeSource;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagClassRegistry;

/**
 * AbstractFileLoader Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AbstractFileLoaderTest extends TestCase
{
    /**
     * @var ThemeSource
     */
    protected $registry;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    /**
     * @var TagClassRegistry
     */
    protected $tagRegistry;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->registry = new ThemeSource();
        $this->tagRegistry = new TagClassRegistry(array(
            'Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'Jungi\Bundle\ThemeBundle\Tag\DesktopDevices',
            'Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Own',
        ));
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel
            ->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(__DIR__.'/Fixtures/FakeBundle'))
        ;
    }
}
