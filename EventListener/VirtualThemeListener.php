<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\EventListener;

use Jungi\Bundle\ThemeBundle\Changer\ThemeChangerEvents;
use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Matcher\VirtualThemeMatcherInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * VirtualThemeListener
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeListener implements EventSubscriberInterface
{
    /**
     * @var VirtualThemeMatcherInterface
     */
    private $matcher;

    /**
     * Constructor
     *
     * @param VirtualThemeMatcherInterface $matcher A virtual theme matcher
     */
    public function __construct(VirtualThemeMatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     *
     */
    public function onResolvedTheme(ResolvedThemeEvent $event)
    {
        $theme = $event->getTheme();
        if (!$theme instanceof VirtualThemeInterface) {
            return;
        }

        $this->handle($theme, $event->getRequest());
    }

    public function onChangedTheme(HttpThemeEvent $event)
    {
        $theme = $event->getTheme();
        if (!$theme instanceof VirtualThemeInterface || null !== $theme->getDecoratedTheme()) {
            return;
        }

        $this->handle($theme, $event->getRequest());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ThemeSelectorEvents::RESOLVED => array('onResolvedTheme', 10),
            ThemeChangerEvents::CHANGED => array('onChangedTheme', 10),
        );
    }

    private function handle(VirtualThemeInterface $theme, Request $request)
    {
        $theme->setDecoratedTheme($this->matcher->match($theme, $request));
    }
}
