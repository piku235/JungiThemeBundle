<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Tag;

use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tag\VirtualTheme;

/**
 * VirtualTheme tag test case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeTest extends TestCase
{
    /**
     * @dataProvider getMatchingTags
     */
    public function testWhenEqual(VirtualTheme $firstTag, VirtualTheme $secondTag)
    {
        $this->assertTrue($firstTag->isEqual($secondTag));
        $this->assertTrue($secondTag->isEqual($firstTag));
    }

    /**
     * @dataProvider getNonMatchingTags
     */
    public function testWhenNotEqual(VirtualTheme $firstTag, VirtualTheme $secondTag)
    {
        $this->assertFalse($firstTag->isEqual($secondTag));
        $this->assertFalse($secondTag->isEqual($firstTag));
    }

    /**
     * Data provider
     */
    public function getMatchingTags()
    {
        return array(
            array(new VirtualTheme('footheme'), new VirtualTheme('footheme')),
            array(new VirtualTheme('bootheme'), new VirtualTheme('bootheme')),
        );
    }

    /**
     * Data provider
     */
    public function getNonMatchingTags()
    {
        return array(
            array(new VirtualTheme('footheme'), new VirtualTheme('footheme_boo')),
            array(new VirtualTheme('bootheme'), new VirtualTheme('')),
        );
    }
}