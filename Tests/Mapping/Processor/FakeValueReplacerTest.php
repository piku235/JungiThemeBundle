<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Information\Author;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeInfoImporter;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Mapping\Processor\FakeValueReplacer;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * FakeValueReplacerTest.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FakeValueReplacerTest extends TestCase
{
    /**
     * @var FakeValueReplacer
     */
    private $replacer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->replacer = new FakeValueReplacer();
    }

    public function testPassingByValue()
    {
        $registry = new ThemeDefinitionRegistry();

        $info = ThemeInfoEssence::createBuilder()
            ->setName('not_important')
            ->setDescription('not_important')
            ->addAuthor(new Author('not_important', 'not_important', 'not_important'))
            ->getThemeInfo();
        $info = ThemeInfoImporter::import($info);

        $tag = new Tag('bar', array(
            'not_important',
            array(
                array(
                    'inner' => 'not_important',
                    'another_inner' => array('not_important'),
                ),
            ),
        ));
        $theme = new StandardThemeDefinition(__DIR__);
        $theme->addTag($tag);
        $theme->setInformation($info);
        $registry->registerThemeDefinition('foo', $theme);

        // The final point
        $expectedInfo = ThemeInfoEssence::createBuilder()
            ->setName('replaced')
            ->setDescription('replaced')
            ->addAuthor(new Author('replaced', 'replaced', 'replaced'))
            ->getThemeInfo();
        $expectedInfo = ThemeInfoImporter::import($expectedInfo);

        $expectedTag = new Tag('bar', array(
            'replaced',
            array(
                array(
                    'inner' => 'replaced',
                    'another_inner' => array('replaced'),
                ),
            ),
        ));

        // Process
        $this->replacer->process($registry);

        // Assert
        $this->assertEquals($expectedInfo, $info);
        $this->assertEquals($expectedTag, $tag);
    }
}
