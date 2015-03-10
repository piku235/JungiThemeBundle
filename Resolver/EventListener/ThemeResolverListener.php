<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Resolver\EventListener;

use Jungi\Bundle\ThemeBundle\Resolver\ResponseWriterInterface;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ThemeResolverListener.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeResolverListener implements EventSubscriberInterface
{
    /**
     * @var ThemeResolverInterface
     */
    private $resolver;

    /**
     * Constructor.
     *
     * @param ThemeResolverInterface $resolver A theme resolver
     */
    public function __construct(ThemeResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Saves theme changes done in the request only when the theme resolver
     * implements the ResponseWriterInterface.
     *
     * @param FilterResponseEvent $event An event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->resolver instanceof ResponseWriterInterface) {
            return;
        }

        $this->resolver->writeResponse($event->getRequest(), $event->getResponse());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}
