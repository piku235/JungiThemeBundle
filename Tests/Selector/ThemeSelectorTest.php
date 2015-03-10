<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Selector;

use Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener;
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelector;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistry;
use Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation\FakeMetadataFactory;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ThemeSelector Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeSelectorTest extends TestCase
{
    /**
     * @var ThemeSelector
     */
    private $selector;

    /**
     * @var ThemeRegistryInterface
     */
    private $registry;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var InMemoryThemeResolver
     */
    private $resolver;

    /**
     * Set up.
     */
    protected function setUp()
    {
        $theme = $this->createThemeMock('footheme');
        $theme
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue(new TagCollection(array(
                new Tag\DesktopDevices(),
            ))));

        $this->eventDispatcher = new EventDispatcher();
        $this->registry = new ThemeRegistry(array(
            $theme,
        ));
        $this->resolver = new InMemoryThemeResolver('footheme', false);
        $this->selector = new ThemeSelector($this->registry, $this->eventDispatcher, $this->resolver);
    }

    /**
     * Tests the fallback functionality when a theme has been invalidated.
     */
    public function testFallbackOnInvalidatedTheme()
    {
        // Add the ValidationListener
        $this->eventDispatcher->addSubscriber(new ValidationListener($this->getValidator()));

        // Add the default theme
        $this->registry->registerTheme($this->createThemeMock('default'));

        // Prepare the request
        $request = $this->createDesktopRequest();
        $this->resolver->setThemeName('footheme', $request);

        // Sets the fallback theme resolver
        $this->setUpFallbackResolver('default');

        // Execute
        $theme = $this->selector->select($request);

        // Assert
        $this->assertEquals('default', $theme->getName());
    }

    /**
     * Tests the fallback functionality when a real theme is not exist.
     */
    public function testFallbackOnEmptyTheme()
    {
        // Default theme
        $this->registry->registerTheme($this->createThemeMock('default'));

        // Prepare the request
        $request = $this->createDesktopRequest();
        $this->resolver->setThemeName(null, $request);

        // Sets the fallback theme resolver
        $this->setUpFallbackResolver('default');

        // Execute
        $theme = $this->selector->select($request);

        // Assert
        $this->assertEquals('default', $theme->getName());
    }

    /**
     * Tests the fallback functionality when a real theme is not exist.
     */
    public function testFallbackOnNonExistingTheme()
    {
        // Default theme
        $this->registry->registerTheme($this->createThemeMock('default'));

        // Prepare the request
        $request = $this->createDesktopRequest();
        $this->resolver->setThemeName('missing_theme', $request);

        // Sets the fallback theme resolver
        $this->setUpFallbackResolver('default');

        // Execute
        $theme = $this->selector->select($request);

        // Assert
        $this->assertEquals('default', $theme->getName());
    }

    /**
     * Tests the fallback functionality when a real theme is exist.
     */
    public function testFallbackOnExistingTheme()
    {
        // Default theme
        $this->registry->registerTheme($this->createThemeMock('default'));

        // Prepare the request
        $request = $this->createDesktopRequest();

        // Sets the fallback theme resolver
        $this->setUpFallbackResolver('default');

        // Execute
        $theme = $this->selector->select($request);

        // Assert
        $this->assertEquals('footheme', $theme->getName());
    }

    /**
     * Tests on an empty theme name.
     *
     * @expectedException \Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException
     */
    public function testFallbackOnMissingTheme()
    {
        $request = $this->createDesktopRequest();

        $this->resolver->setThemeName('missing_theme', $request);

        // Sets the fallback theme resolver
        $this->setUpFallbackResolver(null);

        // Execute
        $this->selector->select($request);
    }

    /**
     * Tests the behaviour in situations when a theme has been invalidated e.g. by validation process.
     *
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException
     */
    public function testOnInvalidatedTheme()
    {
        // Add the ValidationListener
        $this->eventDispatcher->addSubscriber(new ValidationListener($this->getValidator()));

        // Execute
        $request = $this->createDesktopRequest();
        $this->selector->select($request);
    }

    /**
     * Tests on an existing theme.
     */
    public function testOnExistingTheme()
    {
        $request = $this->createDesktopRequest();
        $theme = $this->selector->select($request);

        $this->assertEquals('footheme', $theme->getName());
    }

    /**
     * Tests on an empty theme name.
     *
     * @expectedException \Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException
     */
    public function testOnNullTheme()
    {
        $request = $this->createDesktopRequest();

        $this->resolver->setThemeName(null, $request);
        $this->selector->select($request);
    }

    /**
     * Tests on a bad request.
     *
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException
     */
    public function testOnNonExistingTheme()
    {
        $request = $this->createDesktopRequest();
        $this->resolver->setThemeName('footheme_missing', $request);

        $this->selector->select($request);
    }

    /**
     * Returns the configured validator helper.
     *
     * @return ValidatorInterface
     */
    private function getValidator()
    {
        $metadataFactory = new FakeMetadataFactory();
        $validator = Validation::createValidatorBuilder()
            ->setMetadataFactory($metadataFactory)
            ->getValidator();

        // Constraints for the ThemeInterface
        $metadata = new ClassMetadata('Jungi\Bundle\ThemeBundle\Core\ThemeInterface');
        $metadata->addGetterConstraint('name', new Constraints\EqualTo('default'));
        $metadataFactory->addMetadata($metadata);

        return $validator;
    }

    private function setUpFallbackResolver($themeName)
    {
        $ref = new \ReflectionObject($this->selector);
        $property = $ref->getProperty('fallback');
        $property->setAccessible(true);
        $property->setValue($this->selector, new InMemoryThemeResolver($themeName));
    }
}
