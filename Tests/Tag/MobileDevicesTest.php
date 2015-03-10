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
 * MobileDevices tag test case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class MobileDevicesTest extends TestCase
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
     * Data provider.
     */
    public function getMatchingTags()
    {
        return array(
            array(new MobileDevices(), new MobileDevices()),
            array(new MobileDevices(), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices('iOS'), new MobileDevices()),
            array(new MobileDevices('iOS'), new MobileDevices('iOS')),
            array(new MobileDevices('iOS'), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices(array('AndroidOS', 'WindowsPhoneOS')), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
            array(new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS')), new MobileDevices(array('iOS', 'AndroidOS', 'WindowsPhoneOS'))),
        );
    }

    /**
     * Data provider.
     */
    public function getNonMatchingTags()
    {
        return array(
            array(new MobileDevices(), new TabletDevices()),
            array(new MobileDevices('iOS'), new MobileDevices('AndroidOS')),
            array(new MobileDevices('iOS'), new MobileDevices('AndroidOS', 'WindowsPhoneOS')),
            array(new MobileDevices('iOS'), new TabletDevices(array('iOS', 'WindowsPhoneOS'))),
        );
    }
}
