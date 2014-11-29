<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;

/**
 * FakeMetadataFactory with interface support
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FakeMetadataFactory extends LazyLoadingMetadataFactory
{
    public function addMetadata(ClassMetadata $metadata)
    {
        $this->loadedClasses[$metadata->getClassName()] = $metadata;
    }
}
