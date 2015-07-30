<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Resolver\Filter;

use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Symfony\Component\HttpFoundation\Request;

/**
 * The goal of this filter is the best theme match for a device that sent the request.
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
     * Constructor.
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
            if ($this->mobileDetect->isTablet()) {
                $tag = new Tag\TabletDevices(
                    $this->mobileDetect->detectOS()
                );
            } else {
                $tag = new Tag\MobileDevices(
                    $this->mobileDetect->detectOS()
                );
            }
        } else {
            $tag = new Tag\DesktopDevices();
        }

        $supported = array(
            Tag\DesktopDevices::getName(),
            Tag\MobileDevices::getName(),
            Tag\TabletDevices::getName(),
        );
        foreach ($themes as $theme) {
            /** @var ThemeInterface $theme */
            $tags = $theme->getTags();
            if ($tags->hasSet($supported, Tag\TagCollection::COND_OR) && !$tags->contains($tag)) {
                $themes->remove($theme->getName());
            }
        }
    }
}
