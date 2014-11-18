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
use Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException;
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ThemeHolderListener
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeHolderListener implements EventSubscriberInterface
{
    /**
     * @var ThemeSelectorInterface
     */
    private $selector;

    /**
     * @var ThemeHolderInterface
     */
    private $holder;

    /**
     * @var bool
     */
    private $ignoreNullTheme;

    /**
     * Constructor
     *
     * @param ThemeHolderInterface   $holder          A theme holder
     * @param ThemeSelectorInterface $selector        A theme selector
     * @param bool                   $ignoreNullTheme Whether to ignore the situation when the theme selector
     *                                                will not match any theme for the request (optional)
     */
    public function __construct(ThemeHolderInterface $holder, ThemeSelectorInterface $selector, $ignoreNullTheme = false)
    {
        $this->selector = $selector;
        $this->holder = $holder;
        $this->ignoreNullTheme = (bool) $ignoreNullTheme;
    }

    /**
     * Handles an event in aim to get the theme for the current request
     *
     * @param FilterControllerEvent $event An event
     *
     * @return void
     *
     * @throws NullThemeException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        try {
            $theme = $this->selector->select($event->getRequest());
        } catch (NullThemeException $e) {
            if ($this->ignoreNullTheme) {
                return;
            }

            throw $e;
        }

        $this->holder->setTheme($theme);
    }

    /**
     * Handles the change theme event
     *
     * @param HttpThemeEvent $event An event
     *
     * @return void
     */
    public function onChange(HttpThemeEvent $event)
    {
        $this->holder->setTheme($event->getTheme());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', -100),
            ThemeChangerEvents::POST_CHANGE => array('onChange')
        );
    }
}
