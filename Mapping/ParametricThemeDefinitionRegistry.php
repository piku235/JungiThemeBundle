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

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * ParametricThemeDefinitionRegistry.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParametricThemeDefinitionRegistry extends ThemeDefinitionRegistry implements ParametricThemeDefinitionRegistryInterface
{
    /**
     * @var ParameterBag
     */
    protected $parameterBag;

    /**
     * Constructor.
     *
     * @param ParameterBagInterface $parameterBag A parameter bag (optional)
     */
    public function __construct(ParameterBagInterface $parameterBag = null)
    {
        $this->parameterBag = $parameterBag ?: new ParameterBag();
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $params)
    {
        $this->parameterBag->add($params);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        $this->parameterBag->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return $this->parameterBag->has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        return $this->parameterBag->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameterBag->all();
    }

    /**
     * Returns the parameter bag.
     *
     * @return ParameterBag
     */
    public function getParameterBag()
    {
        return $this->parameterBag;
    }
}
