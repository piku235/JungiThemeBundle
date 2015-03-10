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

use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference as BaseTemplateReference;

/**
 * TemplateFilenameParser.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateFilenameParser implements TemplateNameParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($name)
    {
        if ($name instanceof BaseTemplateReference) {
            return $name;
        }

        $parts = explode('/', strtr($name, '\\', '/'));

        $elements = explode('.', array_pop($parts));
        if (3 > count($elements)) {
            return false;
        }
        $bundle = array_shift($parts);
        $engine = array_pop($elements);
        $format = array_pop($elements);

        return new BaseTemplateReference($bundle, implode('/', $parts), implode('.', $elements), $format, $engine);
    }
}
