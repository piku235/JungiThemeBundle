<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Resolver\Investigator;

use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;

/**
 * ThemeResolverInvestigator is a simple implementation of the ThemeResolverInvestigatorInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeResolverInvestigator implements ThemeResolverInvestigatorInterface
{
    /**
     * @var array
     */
    private $suspects;

    /**
     * Constructor
     *
     * @param array $suspects Suspect theme resolvers (optional)
     */
    public function __construct(array $suspects = array())
    {
        $this->suspects = array();
        foreach ($suspects as $resolver) {
            $this->add($resolver);
        }
    }

    /**
     * Adds a suspect theme resolver
     *
     * @param ThemeResolverInterface|string $class An object or name of a class
     *
     * @return void
     *
     * @throws \InvalidArgumentException When the $class argument will be wrong
     */
    public function add($class)
    {
        if ($class instanceof ThemeResolverInterface) {
            $class = get_class($class);
        } else if (is_string($class)) {
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
     * Returns all suspects theme resolvers
     *
     * @return array
     */
    public function getAll()
    {
        return $this->suspects;
    }

    /**
     * Checks if a given theme resolver is suspect
     *
     * @param ThemeResolverInterface $resolver A theme resolver
     *
     * @return boolean
     */
    public function isSuspect(ThemeResolverInterface $resolver)
    {
        return in_array(get_class($resolver), $this->suspects);
    }
}