<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\OptionsResolver\Options;

/**
 * The select theme type
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeType extends AbstractType
{
    /**
     * @var ThemeManagerInterface
     */
    private $manager;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface $manager A theme manager
     */
    public function __construct(ThemeManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     *
     * Additional options:
     * 'theme_manager' used to get themes
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choiceList = function (Options $options) {
            /* @var \Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface $manager */
            $manager = $options['theme_manager'];

            $choices = empty($options['choices'])
                ? $manager->getThemes()
                : (!is_callable($options['choices'])
                    ? $options['choices']
                    : $options['choices']($manager)
                );
            if (!is_array($choices)) {
                throw new \UnexpectedValueException('The "choices" option should be an array.');
            }

            return new ObjectChoiceList($choices, 'details.name', $options['preferred_choices'], null, 'name');
        };

        $resolver->setDefaults(array(
            'theme_manager' => $this->manager,
            'choice_list' => $choiceList
        ));
        $resolver->setAllowedTypes(array(
            'theme_manager' => 'Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jungi_theme';
    }
}
