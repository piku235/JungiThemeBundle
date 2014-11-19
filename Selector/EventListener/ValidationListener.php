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

use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $suspects;

    /**
     * Constructor
     *
     * @param ValidatorInterface $validator A validator
     * @param LoggerInterface    $logger    A logger (optional)
     * @param array              $suspects  Suspect theme resolvers (optional)
     */
    public function __construct(ValidatorInterface $validator, LoggerInterface $logger = null, array $suspects = array())
    {
        $this->validator = $validator;
        $this->logger = $logger;
        $this->suspects = array();
        foreach ($suspects as $resolver) {
            $this->addSuspect($resolver);
        }
    }

    /**
     * Adds a suspect theme resolver
     *
     * @param ThemeResolverInterface|string $class An object or a class name
     *
     * @return void
     *
     * @throws \InvalidArgumentException When the $class argument will be wrong
     */
    public function addSuspect($class)
    {
        if ($class instanceof ThemeResolverInterface) {
            $class = get_class($class);
        } elseif (is_string($class)) {
            $ref = new \ReflectionClass($class);
            if (!$ref->implementsInterface('Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface')) {
                throw new \InvalidArgumentException(sprintf('The given class "%s" must implement the ThemeResolverInterface.', $class));
            }
        } else {
            throw new \InvalidArgumentException('The $class variable should be a class or an object.');
        }

        $this->suspects[] = $class;
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
        if (!$event->canCancel()) {
            return;
        }

        // If is a trusted theme resolver, omit the validation
        if ($this->suspects && !in_array(get_class($event->getThemeResolver()), $this->suspects)) {
            return;
        }

        $constraints = $this->validator->validate($event->getTheme());
        if (count($constraints)) {
            // Invalidate the theme
            $event->cancel();

            if (null !== $this->logger) {
                $this->logger->warning(ValidationUtils::formatValidationMessage(
                    sprintf('The theme "%s" will be invalidated due to failed validation.', $event->getTheme()->getName()),
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
            ThemeSelectorEvents::RESOLVED_THEME => array('onResolvedTheme', -100),
        );
    }
}
