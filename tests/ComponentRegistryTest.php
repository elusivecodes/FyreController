<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Controller\Component;
use Fyre\Controller\ComponentRegistry;
use Fyre\Controller\Exceptions\ControllerException;
use Fyre\Server\ClientResponse;
use Fyre\Server\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tests\Mock\MockController;

final class ComponentRegistryTest extends TestCase
{

    public function testGetNamespaces(): void
    {
        $this->assertSame(
            [
                '\Tests\Mock\Components\\'
            ],
            ComponentRegistry::getNamespaces()
        );
    }

    public function testHasNamespace(): void
    {
        $this->assertTrue(
            ComponentRegistry::hasNamespace('Tests\Mock\Components')
        );
    }

    public function testHasNamespaceInvalid(): void
    {
        $this->assertFalse(
            ComponentRegistry::hasNamespace('Tests\Invalid\Components')
        );
    }

    public function testRemoveNamespace(): void
    {
        $this->assertTrue(
            ComponentRegistry::removeNamespace('Tests\Mock\Components')
        );

        $this->assertFalse(
            ComponentRegistry::hasNamespace('Tests\Mock\Components')
        );
    }

    public function testRemoveNamespaceInvalid(): void
    {
        $this->assertFalse(
            ComponentRegistry::removeNamespace('Tests\Invalid\Components')
        );
    }

    public function testFind(): void
    {
        $this->assertSame(
            '\Tests\Mock\Components\TestComponent',
            ComponentRegistry::find('Test')
        );
    }

    public function testFindInvalid(): void
    {
        $this->assertNull(
            ComponentRegistry::find('Invalid')
        );
    }

    public function testLoad(): void
    {
        $request = new ServerRequest();
        $response = new ClientResponse();

        $controller = new MockController($request, $response);

        $this->assertInstanceOf(
            Component::class,
            ComponentRegistry::load('Test', $controller)
        );
    }

    public function testLoadInvalid(): void
    {
        $this->expectException(ControllerException::class);

        $request = new ServerRequest();
        $response = new ClientResponse();

        $controller = new MockController($request, $response);

        ComponentRegistry::load('Invalid', $controller);
    }

    public function testNamespaceNoLeadingSlash(): void
    {
        ComponentRegistry::clear();
        ComponentRegistry::addNamespace('Tests\Mock\Components');

        $request = new ServerRequest();
        $response = new ClientResponse();

        $controller = new MockController($request, $response);

        $this->assertInstanceOf(
            Component::class,
            ComponentRegistry::load('Test', $controller)
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        ComponentRegistry::clear();
        ComponentRegistry::addNamespace('\Tests\Mock\Components\\');

        $request = new ServerRequest();
        $response = new ClientResponse();

        $controller = new MockController($request, $response);

        $this->assertInstanceOf(
            Component::class,
            ComponentRegistry::load('Test', $controller)
        );
    }

    public function setUp(): void
    {
        ComponentRegistry::clear();
        ComponentRegistry::addNamespace('Tests\Mock\Components');
    }

}
