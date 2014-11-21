<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Core;

use Jungi\Bundle\ThemeBundle\Core\ThemeNameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReference;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ThemeNameParser Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeNameParserTest extends TestCase
{
    /**
     * @dataProvider getValidNames
     */
    public function testOnValidName($name, $reference)
    {
        $parser = new ThemeNameParser();
        $this->assertEquals($reference, $parser->parse($name));
        $this->assertEquals($reference, $parser->parse($reference));
    }

    public function getValidNames()
    {
        return array(
            array('footheme', new ThemeNameReference('footheme')),
            array('@bartheme', new ThemeNameReference('bartheme', true)),
        );
    }
}
