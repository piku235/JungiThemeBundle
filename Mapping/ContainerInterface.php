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
 * ContainerInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ContainerInterface extends ThemeDefinitionRegistryInterface
{
    /**
     * Sets parameters.
     *
     * @param array $params Parameters
     */
    public function setParameters(array $params);

    /**
     * Sets a parameter.
     *
     * @param string $name  A name
     * @param mixed  $value A value
     */
    public function setParameter($name, $value);

    /**
     * Checks if a given parameter exists.
     *
     * @param string $name A name
     *
     * @return bool
     */
    public function hasParameter($name);

    /**
     * Returns the parameter value.
     *
     * @param string $name A name
     *
     * @return mixed Null if it doesn't exist
     */
    public function getParameter($name);

    /**
     * Returns the all parameters.
     *
     * @return array
     */
    public function getParameters();
}
