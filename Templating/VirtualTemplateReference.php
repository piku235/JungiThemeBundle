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

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference as BaseTemplateReference;

/**
 * VirtualTemplateReference
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualTemplateReference extends TemplateReference
{
    /**
     * Constructor
     *
     * @param BaseTemplateReference $template     A template reference
     * @param string                $theme        A theme name
     * @param string                $pointedTheme A pointed theme name
     */
    public function __construct(BaseTemplateReference $template, $theme, $pointedTheme)
    {
        parent::__construct($template, $theme);

        $this->parameters['pointed_theme'] = $pointedTheme;
    }

    /**
     * Returns the logical name
     *
     * @return string
     */
    public function getLogicalName()
    {
        return sprintf('%s.%s%s%s:%s:%s.%s.%s',
            $this->parameters['theme'],
            $this->parameters['pointed_theme'],
            self::DELIMITER,
            $this->parameters['bundle'],
            $this->parameters['controller'],
            $this->parameters['name'],
            $this->parameters['format'],
            $this->parameters['engine']
        );
    }
}
