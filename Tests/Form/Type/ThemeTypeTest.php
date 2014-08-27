<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Jungi\Bundle\ThemeBundle\Form\Type\ThemeType;
use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Core\Details;

/**
 * ThemeType Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeTypeTest extends TypeTestCase
{
    public function testSubmitValid()
    {
        $first = new Theme('footheme', 'path', new Details(array(
            'name' => 'foo super theme',
            'version' => '1.0.0'
        )));
        $second = new Theme('bootheme', 'path', new Details(array(
            'name' => 'boo hio theme',
            'version' => '1.0.0'
        )));
        $manager = new ThemeManager(array(
            $first, $second
        ));

        $form = $this->factory->create(new ThemeType($manager));
        $form->submit('footheme');
        $view = $form->createView();

        // First check
        $this->assertSame($first, $form->getData());

        // Verify theme names
        foreach ($view->vars['choices'] as $choice) {
            $this->assertAttributeEquals($manager->getTheme($choice->value)->getDetails()->getName(), 'label', $choice);
        }
    }
}
