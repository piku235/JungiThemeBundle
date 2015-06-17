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

use Jungi\Bundle\ThemeBundle\Mapping\Constant;
use Jungi\Bundle\ThemeBundle\Mapping\Processor\ConstantValueReplacer;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistry;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Fake as FakeTag;

/**
 * ConstantValueReplacer Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ConstantValueReplacerTest extends TestCase
{
    /**
     * @var ConstantValueReplacer
     */
    private $replacer;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        if (!defined('CONST_TEST')) {
            define('CONST_TEST', 'testing');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->replacer = new ConstantValueReplacer($this->createTagClassRegistry());
    }

    public function testReplacingConstant()
    {
        // Prepare
        $registry = new ThemeDefinitionRegistry();

        $tag = new Tag('bar', array(
            new Constant('jungi.fake::SPECIAL'),
            new Constant('Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag\Fake::SPECIAL'),
            new Constant('CONST_TEST'),
        ));
        $registry->registerThemeDefinition('foo', new StandardThemeDefinition(__DIR__, array($tag)));

        // Process
        $this->replacer->process($registry);

        // Assert
        $expectedTag = new Tag('bar', array(
            FakeTag::SPECIAL,
            FakeTag::SPECIAL,
            CONST_TEST,
        ));

        $this->assertEquals($expectedTag, $tag);
    }

    public function testOnMissingConstant()
    {
        // Prepare
        $registry = new ThemeDefinitionRegistry();

        $tag = new Tag('bar', array(
            new Constant('__MISSING_CONST'),
        ));
        $registry->registerThemeDefinition('foo', new StandardThemeDefinition(__DIR__, array($tag)));

        // Assert
        try {
            $this->replacer->process($registry);

            $this->fail('InvalidArgumentException should be thrown.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringStartsWith('The constant "__MISSING_CONST" is wrong', $e->getMessage());
        }
    }
}
