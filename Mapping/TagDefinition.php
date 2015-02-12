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
 * TagDefinition
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $args;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addArgument($value)
    {
        $this->args[] = $value;
    }

    public function setArgument($index, $value)
    {
        $this->args[$index] = $value;
    }

    public function setArguments(array $args)
    {
        $this->args = $args;
    }

    public function getArguments()
    {
        return $this->args;
    }
}
