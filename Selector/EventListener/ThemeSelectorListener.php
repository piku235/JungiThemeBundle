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

use Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ThemeSelectorListener
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeSelectorListener implements EventSubscriberInterface
{
    /**
     * @var ThemeSelectorInterface
     */
    private $selector;

    /**
     * Constructor
     *
     * @param ThemeSelectorInterface $selector A theme selector
     */
    public function __construct(ThemeSelectorInterface $selector)
    {
        $this->selector = $selector;
    }

    /**
     * Applies theme from a request
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->selector->select($event->getRequest());
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents()
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', -100)
        );
    }
}