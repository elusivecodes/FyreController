<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Controller\Component,
    Fyre\Controller\ComponentRegistry,
    Fyre\Controller\Exceptions\ControllerException,
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    PHPUnit\Framework\TestCase,
    Tests\Mock\MockController;

final class ComponentRegistryTest extends TestCase
{

    public function testFind(): void
    {
        $this->assertSame(
            '\Tests\Mock\Components\Test',
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

    public static function setUpBeforeClass(): void
    {
        ComponentRegistry::clear();
        ComponentRegistry::addNamespace('Tests\Mock\Components');
    }

}
