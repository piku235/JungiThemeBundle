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
use Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ValidationListener.
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
     * @var array
     */
    private $suspects;

    /**
     * Constructor.
     *
     * @param ValidatorInterface $validator A validator
     * @param array              $suspects  Suspect theme resolvers (optional)
     */
    public function __construct(ValidatorInterface $validator, array $suspects = array())
    {
        $this->validator = $validator;
        $this->suspects = array();
        foreach ($suspects as $resolver) {
            $this->addSuspect($resolver);
        }
    }

    /**
     * Adds a suspect theme resolver.
     *
     * When they are suspect theme resolver only for them the validation
     * will be performed
     *
     * @param ThemeResolverInterface|string $class An object or a class name
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
     * Validates the theme from an event.
     *
     * @param ResolvedThemeEvent $event An event
     *
     * @throws ThemeValidationException If the theme will not pass a validation
     */
    public function onResolvedTheme(ResolvedThemeEvent $event)
    {
        // If is a trusted theme resolver, omit the validation
        if ($this->suspects && !in_array(get_class($event->getThemeResolver()), $this->suspects)) {
            return;
        }

        $constraints = $this->validator->validate($event->getTheme());
        if (count($constraints)) {
            throw ThemeValidationException::createWellFormatted(
                sprintf('The theme "%s" will be invalidated due to failed validation.', $event->getTheme()->getName()),
                $constraints
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ThemeSelectorEvents::RESOLVED => array('onResolvedTheme', -100),
        );
    }
}
