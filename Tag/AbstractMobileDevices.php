<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag;

/**
 * AbstractMobileDevices
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AbstractMobileDevices implements TagInterface
{
    /**
     * @var array
     */
    protected $systems;

    /**
     * Constructor
     *
     * @param string|array $systems Operating systems (optional)
     *                              Operating systems should be the same as in the MobileDetect class
     */
    public function __construct($systems = array())
    {
        $this->systems = (array) $systems;
    }

    /**
     * Returns the operating systems
     *
     * @return array
     */
    public function getSystems()
    {
        return $this->systems;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqual(TagInterface $tag)
    {
        return $tag instanceof static && ((!$this->systems || !$tag->systems) || array_intersect($this->systems, $tag->systems));
    }
}
