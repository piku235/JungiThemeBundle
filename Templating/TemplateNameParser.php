<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Templating;

use Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser as BaseTemplateNameParser;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference as BaseTemplateReference;

/**
 * TemplateNameParser basically wraps a TemplateReferenceInterface instance with the TemplateReference
 * using the current theme. If the current theme is not available then the parent parse method will be
 * used.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateNameParser extends BaseTemplateNameParser
{
    /**
     * @var ThemeHolderInterface
     */
    private $holder;

    /**
     * Constructor
     *
     * @param ThemeHolderInterface $holder A theme holder
     * @param KernelInterface      $kernel A KernelInterface instance
     */
    public function __construct(ThemeHolderInterface $holder, KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $this->holder = $holder;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        } elseif (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        // The current theme
        $theme = $this->holder->getTheme();

        // Use parent method if there is no theme
        $parent = parent::parse($name);
        if (null === $theme || !$parent instanceof BaseTemplateReference) {
            return $parent;
        }

        return $this->cache[$name] = new TemplateReference($parent, $theme->getName());
    }
}
