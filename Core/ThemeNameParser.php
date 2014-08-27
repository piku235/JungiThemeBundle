<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser;

/**
 * ThemeNameParser wraps a TemplateReferenceInterface instance with the ThemeReference using the current theme.
 * If the current theme is not set then the parent parse method will be used.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeNameParser extends TemplateNameParser
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
     * Parses a template name to a theme reference
     *
     * @param TemplateReferenceInterface|string $name A template name
     *
     * @return ThemeReference|TemplateReference
     */
    public function parse($name)
    {
        $theme = $this->holder->getTheme();

        // Use parent method if there is no theme
        if (null === $theme) {
            return parent::parse($name);
        }

        $reference = null;
        if ($name instanceof TemplateReferenceInterface) {
            $reference = $name;
            $name = $reference->getLogicalName();
        } elseif (isset($this->cache[$name])) {
            return $this->cache[$name];
        } else {
            $reference = parent::parse($name);
        }

        return $this->cache[$name] = new ThemeReference($reference, $theme->getName());
    }
}
