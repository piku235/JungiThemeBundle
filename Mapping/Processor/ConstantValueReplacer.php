<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Mapping\Constant;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagClassRegistryInterface;

/**
 * ConstantValueReplacer processes constant values.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ConstantValueReplacer extends ValueReplacer
{
    /**
     * @var TagClassRegistryInterface
     */
    private $tagClassRegistry;

    /**
     * Constructor.
     *
     * @param TagClassRegistryInterface $tagClassRegistry A tag class registry
     */
    public function __construct(TagClassRegistryInterface $tagClassRegistry)
    {
        $this->tagClassRegistry = $tagClassRegistry;
    }

    /**
     * Resolves a constant value if present.
     *
     * @param string                           $value    A value
     * @param ThemeDefinitionRegistryInterface $registry A theme definition registry
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException When a constant does not exist
     */
    protected function resolveValue($value, ThemeDefinitionRegistryInterface $registry)
    {
        if (!$value instanceof Constant) {
            return $value;
        }

        $val = $value->getValue();
        if (defined($val)) {
            return constant($val);
        }

        // Is the constant located in a class or a tag type?
        if (false !== $pos = strrpos($val, '::')) {
            // A class or a tag type
            $location = substr($val, 0, $pos);
            if (false !== strpos($location, '.')) { // Is the location of a tag?
                $location = $this->tagClassRegistry->getTagClass($location);
            }

            $realConst = $location.substr($val, $pos);
            if (defined($realConst)) {
                return constant($realConst);
            }
        }

        throw new \InvalidArgumentException(sprintf('The constant "%s" is wrong or it can not be found.', $val));
    }
}
