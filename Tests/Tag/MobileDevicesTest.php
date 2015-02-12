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
use Jungi\Bundle\ThemeBundle\Tag\MobileDevices;

/**
 * MobileDevices tag test case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class MobileDevicesTest extends TestCase
{
    /**
     * @dataProvider getMatchingTags
     */
    public function testWhenEqual(MobileDevices $firstTag, MobileDevices $secondTag)
    {
        $this->assertTrue($firstTag->isEqual($secondTag));
        $this->assertTrue($secondTag->isEqual($firstTag));
    }

    /**
     * @dataProvider getNonMatchingTags
     */
    public function testWhenNotEqual(MobileDevices $firstTag, MobileDevices $secondTag)
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
            array(new MobileDevices(), new MobileDevices()),
            array(new MobileDevices(), new MobileDevices(array(), MobileDevices::TABLET)),
            array(new MobileDevices(), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices('iOS'), new MobileDevices()),
            array(new MobileDevices('iOS'), new MobileDevices('iOS')),
            array(new MobileDevices('iOS'), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices(array('AndroidOS', 'WindowsPhoneOS')), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS')), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices('iOS', MobileDevices::MOBILE), new MobileDevices()),
            array(new MobileDevices('iOS', MobileDevices::MOBILE), new MobileDevices(array('iOS', 'AndroidOS'), MobileDevices::MOBILE)),
            array(new MobileDevices('iOS', MobileDevices::MOBILE), new MobileDevices(array(), MobileDevices::MOBILE)),
            array(new MobileDevices('iOS', MobileDevices::TABLET), new MobileDevices()),
            array(new MobileDevices('iOS', MobileDevices::TABLET), new MobileDevices(array('iOS', 'WindowsPhoneOS'), MobileDevices::TABLET)),
            array(new MobileDevices('iOS', MobileDevices::TABLET), new MobileDevices(array(), MobileDevices::TABLET)),
        );
    }

    /**
     * Data provider
     */
    public function getNonMatchingTags()
    {
        return array(
            array(new MobileDevices(array(), MobileDevices::MOBILE), new MobileDevices(array(), MobileDevices::TABLET)),
            array(new MobileDevices('iOS'), new MobileDevices('AndroidOS')),
            array(new MobileDevices('iOS', MobileDevices::TABLET), new MobileDevices('AndroidOS', MobileDevices::TABLET)),
            array(new MobileDevices('iOS'), new MobileDevices('AndroidOS', 'WindowsPhoneOS')),
            array(new MobileDevices('iOS', MobileDevices::MOBILE), new MobileDevices(array('iOS', 'WindowsPhoneOS'), MobileDevices::TABLET)),
        );
    }
}
