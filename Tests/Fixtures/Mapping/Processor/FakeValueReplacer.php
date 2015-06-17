<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Mapping\Processor\ValueReplacer;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;

/**
 * FakeValueReplacer,.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FakeValueReplacer extends ValueReplacer
{
    /**
     * {@inheritdoc}
     */
    protected function resolveValue($value, ThemeDefinitionRegistryInterface $registry)
    {
        return 'replaced';
    }
}
