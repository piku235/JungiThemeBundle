<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Matcher\Filter;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Symfony\Component\HttpFoundation\Request;

/**
 * The main goal of this filter is the best theme match for a device that sent the request
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceThemeFilter implements ThemeFilterInterface
{
    /**
     * @var MobileDetect
     */
    private $mobileDetect;

    /**
     * Constructor
     *
     * @param MobileDetect $mobileDetect A mobile detect instance
     */
    public function __construct(MobileDetect $mobileDetect)
    {
        $this->mobileDetect = $mobileDetect;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(ThemeCollection $themes, Request $request)
    {
        // Handle the request from the event
        $this->mobileDetect->handleRequest($request);

        // Get the tag for match
        if ($this->mobileDetect->isMobile()) { // Is a mobile or a tablet device?
            $tag = new Tag\MobileDevices(
                $this->mobileDetect->detectOS(),
                $this->mobileDetect->isTablet() ? Tag\MobileDevices::TABLET : Tag\MobileDevices::MOBILE
            );
        } else {
            $tag = new Tag\DesktopDevices();
        }

        foreach ($themes as $theme) {
            /* @var ThemeInterface $theme */
            $tags = $theme->getTags();
            if (($tags->has(Tag\DesktopDevices::getName()) || $tags->has(Tag\MobileDevices::getName())) && !$tags->contains($tag)) {
                $themes->remove($theme);
            }
        }
    }
}
