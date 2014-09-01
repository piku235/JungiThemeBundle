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

use Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface;
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
     * Constructor
     *
     * @param ThemeHolderInterface   $holder   A theme holder
     * @param ThemeSelectorInterface $selector A theme selector
     */
    public function __construct(ThemeHolderInterface $holder, ThemeSelectorInterface $selector)
    {
        $this->selector = $selector;
        $this->holder = $holder;
    }

    /**
     * Handles an event in aim to get a theme for the current request
     *
     * @param FilterControllerEvent $event An event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->holder->setTheme($this->selector->select($event->getRequest()));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', -100)
        );
    }
}
