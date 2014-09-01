<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Selector\EventListener;

use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorEvents;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\MobileDetect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The main goal of the class is the best theme match for a device that sent the request
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DeviceThemeSwitch implements EventSubscriberInterface
{
    /**
     * @var MobileDetect
     */
    private $mobileDetect;

    /**
     * @var ThemeManagerInterface
     */
    private $themeManager;

    /**
     * Constructor
     *
     * @param MobileDetect          $mobileDetect A mobile detect instance
     * @param ThemeManagerInterface $manager      A theme manager
     */
    public function __construct(MobileDetect $mobileDetect, ThemeManagerInterface $manager)
    {
        $this->mobileDetect = $mobileDetect;
        $this->themeManager = $manager;
    }

    /**
     * Handles a ResolvedThemeEvent event
     *
     * @param ResolvedThemeEvent $event An event
     *
     * @return void
     */
    public function onResolvedTheme(ResolvedThemeEvent $event)
    {
        // A theme from the event
        $theme = $event->getTheme();

        // Only the representative themes will be handled
        // so the themes which does not have a link tag
        // will be omitted
        if ($theme->getTags()->has(Tag\Link::getName())) {
            return;
        }

        // Handle the request from the event
        $this->mobileDetect->handleRequest($event->getRequest());

        // If none of devices had not match, stop
        if ($this->mobileDetect->isMobile()) { // Is a mobile or a tablet device?
            $tag = new Tag\MobileDevices(
                $this->mobileDetect->detectOS(),
                $this->mobileDetect->isTablet() ? Tag\MobileDevices::TABLET : Tag\MobileDevices::MOBILE
            );
        } else {
            $tag = new Tag\DesktopDevices();
        }

        // Do nothing if a obtained theme has this tag
        if ($theme->getTags()->contains($tag)) {
            return;
        }

        // Look for a substitute theme
        $substituteTheme = $this->themeManager->getThemeWithTags(array(
            new Tag\Link($theme->getName()),
            $tag
        ));

        // Sets a new theme if found
        if (null !== $substituteTheme) {
            $event->setTheme($substituteTheme);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ThemeSelectorEvents::RESOLVED_THEME => array('onResolvedTheme')
        );
    }
}
