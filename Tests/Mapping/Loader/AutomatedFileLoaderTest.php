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
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Own;
use Jungi\Bundle\ThemeBundle\Tag;

/**
 * AbstractFileLoader Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AutomatedFileLoaderTest extends AbstractFileLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        if (!defined('CONST_TEST')) {
            define('CONST_TEST', 'testing');
        }
    }

    public function testFull()
    {
        $this->loadFile('full');

        $leadingAuthor = new Author('piku235', 'piku235@gmail.com', 'www.foo.com');
        $ib = ThemeInfoEssence::createBuilder();
        $ib
            ->setName('A fancy theme')
            ->setVersion('1.0.0')
            ->setDescription('<i>foo desc</i>')
            ->setLicense('MIT')
            ->addAuthor($leadingAuthor)
        ;
        $info = $ib->getInformation();

        $ib->addAuthor(new Author('piku234', 'foo@gmail.com', 'www.boo.com'));
        $info1 = $ib->getInformation();

        $ib = ThemeInfoEssence::createBuilder();
        $ib
            ->setName('A fancy theme')
            ->setVersion('1.0.0')
        ;
        $info4 = $ib->getInformation();

        $ib = ThemeInfoEssence::createBuilder();
        $ib
            ->setName('A fancy theme')
            ->setVersion('1.0.0')
            ->setDescription('')
        ;

        $themes = array(
            new Theme('foo_1', __DIR__ . '/Fixtures/FakeBundle', $info1, new TagCollection(array(
                new Tag\DesktopDevices(),
                new Tag\MobileDevices(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE),
                new Own('test')
            ))),
            new Theme('foo_2', __DIR__ . '/Fixtures/FakeBundle', $info, new TagCollection(array(
                new Own(Own::SPECIAL)
            ))),
            new Theme('foo_3', __DIR__ . '/Fixtures/FakeBundle', $info, new TagCollection(array(
                new Own(CONST_TEST)
            ))),
            new Theme('foo_4', __DIR__ . '/Fixtures/FakeBundle', $info4)
        );

        foreach ($themes as $theme) {
            $this->assertEquals($theme, $this->manager->getTheme($theme->getName()));
        }
    }

    public function testWithoutParameters()
    {
        $this->loadFile('without_parameters');

        $ib = ThemeInfoEssence::createBuilder();
        $ib
            ->setName('A fancy theme')
            ->setVersion('1.0.0')
            ->setDescription('<i>foo desc</i>')
            ->setLicense('MIT')
            ->addAuthor(new Author('piku235', 'piku235@gmail.com', 'www.foo.com'))
            ->addAuthor(new Author('piku234', 'foo@gmail.com', 'www.boo.com'))
        ;

        $theme = new Theme('foo_1', __DIR__ . '/Fixtures/FakeBundle', $ib->getInformation(), new TagCollection(array(
            new Tag\DesktopDevices(),
            new Tag\MobileDevices(array('iOS', 'AndroidOS'), Tag\MobileDevices::MOBILE),
            new Own('test')
        )));

        $this->assertEquals($theme, $this->manager->getTheme('foo_1'));
    }

    /**
     * @dataProvider getInvalidThemeMappings
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidExamples($file)
    {
        $this->loadFile($file);
    }

    /**
     * Invalid theme mappings
     *
     * @return array
     */
    public function getInvalidThemeMappings()
    {
        return array(
            array('bad_parameter'),
            array('info_bad_property_key'),
            array('info_missing_property_key'),
            array('invalid_authors_first'),
            array('invalid_authors_second'),
            array('invalid_authors_third')
        );
    }

    /**
     * Loads the given file
     *
     * @param string $file A file without ext
     *
     * @return void
     */
    abstract protected function loadFile($file);
}
