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
 * Tag.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Tag
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $args = array();

    /**
     * Constructor.
     *
     * @param null  $name
     * @param array $args
     */
    public function __construct($name = null, array $args = array())
    {
        $this->name = $name;
        $this->args = $args;
    }

    /**
     * @param $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     *
     * @return Tag
     */
    public function addArgument($value)
    {
        $this->args[] = $value;

        return $this;
    }

    /**
     * @param $index
     * @param $value
     *
     * @return Tag
     */
    public function setArgument($index, $value)
    {
        $this->args[$index] = $value;

        return $this;
    }

    /**
     * @param array $args
     *
     * @return Tag
     */
    public function setArguments(array $args)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->args;
    }
}
