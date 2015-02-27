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

use Jungi\Bundle\ThemeBundle\Tag\AbstractMobileDevices;
use Jungi\Bundle\ThemeBundle\Tag\TabletDevices;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tag\MobileDevices;

/**
 * TabletDevicesTest tag test case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TabletDevicesTest extends TestCase
{
    /**
     * @dataProvider getMatchingTags
     */
    public function testWhenEqual(AbstractMobileDevices $firstTag, AbstractMobileDevices $secondTag)
    {
        $this->assertTrue($firstTag->isEqual($secondTag));
        $this->assertTrue($secondTag->isEqual($firstTag));
    }

    /**
     * @dataProvider getNonMatchingTags
     */
    public function testWhenNotEqual(AbstractMobileDevices $firstTag, AbstractMobileDevices $secondTag)
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
            array(new TabletDevices(), new TabletDevices()),
            array(new TabletDevices(), new TabletDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new TabletDevices('iOS'), new TabletDevices()),
            array(new TabletDevices('iOS'), new TabletDevices('iOS')),
            array(new TabletDevices('iOS'), new TabletDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new TabletDevices(array('AndroidOS', 'WindowsPhoneOS')), new TabletDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new TabletDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS')), new TabletDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
        );
    }

    /**
     * Data provider
     */
    public function getNonMatchingTags()
    {
        return array(
            array(new MobileDevices(), new TabletDevices()),
            array(new TabletDevices('iOS'), new TabletDevices('AndroidOS')),
            array(new TabletDevices('iOS'), new TabletDevices('AndroidOS', 'WindowsPhoneOS')),
            array(new MobileDevices('iOS'), new TabletDevices(array('iOS', 'WindowsPhoneOS'))),
        );
    }
}
