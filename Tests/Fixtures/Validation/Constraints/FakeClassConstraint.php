<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * FakeClassConstraint.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FakeClassConstraint extends Constraint
{
    public $message = 'Failed.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
