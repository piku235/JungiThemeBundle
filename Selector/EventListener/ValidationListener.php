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

use Jungi\Bundle\ThemeBundle\Resolver\Investigator\ThemeResolverInvestigatorInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Selector\Event\SmartResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorEvents;
use Jungi\Bundle\ThemeBundle\Validation\ValidationUtils;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * ValidationListener
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ValidationListener implements EventSubscriberInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ThemeResolverInvestigatorInterface
     */
    private $investigator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param ValidatorInterface                 $validator    A validator
     * @param ThemeResolverInvestigatorInterface $investigator An untrusted resolvers checker (optional)
     * @param LoggerInterface                    $logger       A logger (optional)
     */
    public function __construct(ValidatorInterface $validator, ThemeResolverInvestigatorInterface $investigator = null, LoggerInterface $logger = null)
    {
        $this->validator = $validator;
        $this->investigator = $investigator;
        $this->logger = $logger;
    }

    /**
     * Sets a theme resolver investigator
     *
     * @param ThemeResolverInvestigatorInterface $investigator A theme resolver investigator
     *
     * @return void
     */
    public function setThemeResolverInvestigator(ThemeResolverInvestigatorInterface $investigator)
    {
        $this->investigator = $investigator;
    }

    /**
     * Validates the theme from an event
     *
     * @param ResolvedThemeEvent $event An event
     *
     * @return void
     */
    public function onResolvedTheme(ResolvedThemeEvent $event)
    {
        if (!$event instanceof SmartResolvedThemeEvent) {
            return;
        }

        // If is a trusted theme resolver, omit the validation
        if (null !== $this->investigator && !$this->investigator->isSuspect($event->getThemeResolver())) {
            return;
        }

        $constraints = $this->validator->validate($event->getTheme());
        if (count($constraints)) {
            // Invalidate the theme
            $event->clearTheme();

            if (null !== $this->logger) {
                $this->logger->warning(ValidationUtils::formatValidationMessage(
                    sprintf('The theme "%s" did not passed the validation, the theme will be invalidated.', $event->getTheme()->getName()),
                    $constraints
                ));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ThemeSelectorEvents::RESOLVED_THEME => array('onResolvedTheme', -100)
        );
    }
}
