<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Selector\EventListener;

use Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\DefaultTranslator;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation\FakeMetadataFactory;
use Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation\Constraints\FakeClassConstraint;

/**
 * ValidationListenerTest Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ValidationListenerTest extends TestCase
{
    /**
     * @var ValidationListener
     */
    private $listener;

    /**
     * @var FakeMetadataFactory
     */
    private $metadataFactory;

    /**
     * @var Theme
     */
    private $theme;

    /**
     * @var ResolvedThemeEvent
     */
    private $event;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->metadataFactory = new FakeMetadataFactory();
        $validator = new Validator($this->metadataFactory, new ConstraintValidatorFactory(), new DefaultTranslator());
        $this->theme = new Theme(
            'footheme', 'path', $this->getMock('Jungi\Bundle\ThemeBundle\Information\ThemeInfo')
        );
        $this->listener = new ValidationListener($validator);
        $this->event = new ResolvedThemeEvent(
            $this->theme->getName(),
            $this->theme,
            new InMemoryThemeResolver('footheme'),
            $this->getMock('Symfony\Component\HttpFoundation\Request')
        );
    }

    /**
     * Tests the validation when it should be executed
     */
    public function testSuspectResolvers()
    {
        $this->listener->addSuspect('Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver');
        $this->listener->addSuspect(new InMemoryThemeResolver());

        // Execute
        $this->prepareFailedValidation();
        $this->listener->onResolvedTheme($this->event);

        // Assert
        $this->assertNull($this->event->getTheme());
    }

    /**
     * Tests the validation when it shouldn't be executed
     */
    public function testTrustedResolvers()
    {
        $this->listener->addSuspect('Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver');

        // Execute
        $this->prepareFailedValidation();
        $this->listener->onResolvedTheme($this->event);

        // Check
        $this->assertSame($this->theme, $this->event->getTheme());
    }

    /**
     * Tests the succeed validation
     */
    public function testSucceedValidation()
    {
        $metadata = new ClassMetadata('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $metadata->addGetterConstraint('name', new Constraints\EqualTo('footheme'));
        $this->metadataFactory->addMetadata($metadata);

        // Execute
        $this->listener->onResolvedTheme($this->event);

        // Check
        $this->assertSame($this->theme, $this->event->getTheme());
    }

    /**
     * Tests the failed validation
     */
    public function testFailedValidation()
    {
        $this->prepareFailedValidation();

        // Execute
        $this->listener->onResolvedTheme($this->event);

        // Check
        $this->assertNull($this->event->getTheme());
    }

    /**
     * Tests the ValidationListener when the clearing theme in an event is disabled
     */
    public function testWhenClearThemeIsDisabled()
    {
        $metadata = new ClassMetadata('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $metadata->addConstraint(new FakeClassConstraint());
        $metadata->addGetterConstraint('name', new Constraints\EqualTo('footheme_boo'));
        $this->metadataFactory->addMetadata($metadata);

        $event = new ResolvedThemeEvent(
            $this->theme->getName(),
            $this->theme,
            $this->getMock('Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface'),
            $this->getMock('Symfony\Component\HttpFoundation\Request'),
            false
        );
        $this->listener->onResolvedTheme($event);

        // Check
        $this->assertNotNull($event->getTheme());
    }

    private function prepareFailedValidation()
    {
        $metadata = new ClassMetadata('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $metadata->addConstraint(new FakeClassConstraint());
        $metadata->addGetterConstraint('name', new Constraints\EqualTo('footheme_boo'));
        $this->metadataFactory->addMetadata($metadata);
    }
}
