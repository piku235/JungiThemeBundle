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

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpFileLoader;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Own;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * PhpFileLoader Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpFileLoaderTest extends AbstractFileLoaderTest
{
    /**
     * @var PhpFileLoader
     */
    private $loader;

    /**
     * @var FileLocator
     */
    private $locator;

    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        $this->locator = new FileLocator($this->kernel, __DIR__ . '/Fixtures/php');
        $this->loader = new PhpFileLoader($this->manager, $this->locator, $this->tagFactory);
    }

    /**
     * @expectedException \DomainException
     */
    public function testLoad()
    {
        $this->loader->load('../yml/full.yml');
    }

    /**
     * Tests file load
     */
    public function testFull()
    {
        $this->loader->load('theme.php');

        $ib = ThemeInfoEssence::createBuilder();
        $ib
            ->setName('A fancy theme')
            ->setVersion('1.0.0')
            ->setDescription('<i>foo desc</i>')
            ->setLicense('MIT')
            ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'))
            ->addAuthor(new Author('piku234', 'foo@gmail.com', 'www.boo.com'))
        ;

        $this->assertEquals(new Theme(
            'foo_1',
            $this->locator->locate('@JungiFooBundle/Resources/theme'),
            $ib->getThemeInfo(),
            new TagCollection(array(
                new Tag\DesktopDevices(),
                new Tag\MobileDevices(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE),
                new Own('test')
            ))
        ), $this->manager->getTheme('foo_1'));
    }
}
