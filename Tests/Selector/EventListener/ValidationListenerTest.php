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
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation\FakeMetadataFactory;
use Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation\Constraints\FakeClassConstraint;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints;

/**
 * ValidationListenerTest Test Case.
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
     * @var ResolvedThemeEvent
     */
    private $event;

    /**
     * Set up.
     */
    protected function setUp()
    {
        $this->metadataFactory = new FakeMetadataFactory();
        $validator = Validation::createValidatorBuilder()
            ->setMetadataFactory($this->metadataFactory)
            ->getValidator();
        $this->listener = new ValidationListener($validator);
        $this->event = new ResolvedThemeEvent(
            $this->createThemeMock('footheme', 'path'),
            new InMemoryThemeResolver('footheme'),
            $this->getMock('Symfony\Component\HttpFoundation\Request')
        );
    }

    /**
     * Tests the failed validation.
     *
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException
     */
    public function testFailedValidation()
    {
        $this->setupFailedValidation();

        // Execute
        $this->listener->onResolvedTheme($this->event);
    }

    /**
     * Tests the validation when it should be executed.
     *
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException
     */
    public function testSuspectResolvers()
    {
        $this->listener->addSuspect('Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver');
        $this->listener->addSuspect(new InMemoryThemeResolver());

        // Execute
        $this->setupFailedValidation();
        $this->listener->onResolvedTheme($this->event);
    }

    /**
     * Tests the validation when it should not be executed.
     */
    public function testTrustedResolvers()
    {
        $this->listener->addSuspect('Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver');

        // Execute
        $this->setupFailedValidation();
        $this->listener->onResolvedTheme($this->event);
    }

    /**
     * Tests the succeed validation.
     */
    public function testSucceedValidation()
    {
        $metadata = new ClassMetadata('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $metadata->addGetterConstraint('name', new Constraints\EqualTo('footheme'));
        $this->metadataFactory->addMetadata($metadata);

        // Execute
        $this->listener->onResolvedTheme($this->event);
    }

    private function setupFailedValidation()
    {
        $metadata = new ClassMetadata('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $metadata->addConstraint(new FakeClassConstraint());
        $metadata->addGetterConstraint('name', new Constraints\EqualTo('footheme_boo'));
        $this->metadataFactory->addMetadata($metadata);
    }
}
