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

use Jungi\Bundle\ThemeBundle\Changer\Event\ChangeThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParserInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReferenceInterface;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Matcher\ThemeMatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ThemeChanger is a simple implementation of the ThemeChangerInterface.
 *
 * It uses a ThemeResolverInterface instance for changing the current theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeChanger implements ThemeChangerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ThemeNameParserInterface
     */
    private $nameParser;

    /**
     * @var ThemeResolverInterface
     */
    private $resolver;

    /**
     * @var ThemeMatcherInterface
     */
    private $matcher;

    /**
     * Constructor
     *
     * @param ThemeMatcherInterface    $matcher    A theme matcher
     * @param ThemeNameParserInterface $nameParser A theme name parser
     * @param ThemeResolverInterface   $resolver   A theme resolver
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(ThemeMatcherInterface $matcher, ThemeNameParserInterface $nameParser, ThemeResolverInterface $resolver, EventDispatcherInterface $dispatcher)
    {
        $this->matcher = $matcher;
        $this->nameParser = $nameParser;
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If the given theme does not implement the ThemeInterface
     *                                   or the ThemeNameReferenceInterface
     */
    public function change($themeName, Request $request)
    {
        if ($themeName instanceof ThemeInterface) {
            $themeName = $this->nameParser->parse($themeName->getName());
        } elseif (is_string($themeName)) {
            $themeName = $this->nameParser->parse($themeName);
        } elseif (!$themeName instanceof ThemeNameReferenceInterface) {
            throw new \InvalidArgumentException('The theme must be a string, an instance of the "ThemeInterface" or the "ThemeNameReferenceInterface".');
        }

        // Theme
        $theme = $this->matcher->match($themeName, $request);

        // Dispatch the event
        $event = new ChangeThemeEvent($themeName, $theme, $request);
        $this->dispatcher->dispatch(ThemeChangerEvents::PRE_CHANGE, $event);

        // Apply
        $this->resolver->setThemeName((string) $themeName, $request);

        // Dispatch the event
        $this->dispatcher->dispatch(ThemeChangerEvents::POST_CHANGE, $event);
    }
}
