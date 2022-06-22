<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Controller\Component,
    Fyre\Controller\ComponentRegistry,
    Fyre\Controller\Controller,
    Fyre\Controller\Exceptions\ControllerException,
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    PHPUnit\Framework\TestCase,
    Tests\Mock\MockController;

final class ComponentTest extends TestCase
{

    protected Controller $controller;

    public function testComponent(): void
    {
        $this->controller->loadComponent('Test');

        $this->assertInstanceOf(
            Component::class,
            $this->controller->Test
        );

        $this->assertSame(
            1,
            $this->controller->Test->value()
        );
    }

    public function testLoadComponentInvalid(): void
    {
        $this->expectException(ControllerException::class);

        $this->controller->loadComponent('Invalid');
    }

    protected function setUp(): void
    {
        $request = new ServerRequest();
        $response = new ClientResponse();

        $this->controller = new MockController($request, $response);
    }

    public static function setUpBeforeClass(): void
    {
        ComponentRegistry::clear();
        ComponentRegistry::addNamespace('Tests\Mock\Components');
    }

}
