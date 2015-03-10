<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping;

/**
 * Constant.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Constant
{
    /**
     * @var string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string $const A constant
     */
    public function __construct($const)
    {
        $this->value = $const;
    }

    /**
     * Returns the value of the constant.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
