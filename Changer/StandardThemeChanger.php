<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Changer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Event\ThemeEvent;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface;

/**
 * StandardThemeChanger is a simple implementation of the ThemeChangerInterface.
 * It uses a ThemeResolverInterface instance for changing the current theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class StandardThemeChanger implements ThemeChangerInterface
{
    /**
     * @var ThemeManagerInterface
     */
    protected $manager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var ThemeHolderInterface
     */
    protected $holder;

    /**
     * @var ThemeResolverInterface
     */
    protected $resolver;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface    $manager    A theme manager
     * @param ThemeHolderInterface     $holder     A theme holder
     * @param ThemeResolverInterface   $resolver   A theme resolver
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(ThemeManagerInterface $manager, ThemeHolderInterface $holder, ThemeResolverInterface $resolver, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->holder = $holder;
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function change($theme, Request $request)
    {
        if (!$theme instanceof ThemeInterface) {
            $theme = $this->manager->getTheme($theme);
        }

        // Dispatch the event
        $event = new ThemeEvent($theme, $this->manager, $this->resolver, $request);
        $this->dispatcher->dispatch(ThemeChangerEvents::PRE_SET, $event);

        // Change the current theme
        $this->holder->setTheme($theme);
        $this->resolver->setThemeName($theme->getName(), $request);

        // Dispatch the event
        $this->dispatcher->dispatch(ThemeChangerEvents::POST_SET, $event);
    }
}
