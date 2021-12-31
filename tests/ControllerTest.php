<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Controller\Controller,
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    RuntimeException,
    Tests\Mock\MockController;

final class ControllerTest extends TestCase
{

    protected Controller $controller;

    public function testRequest(): void
    {
        $this->assertInstanceOf(
            ServerRequest::class,
            $this->controller->getRequest()
        );
    }

    public function testResponse(): void
    {
        $this->assertInstanceOf(
            ClientResponse::class,
            $this->controller->getResponse()
        );
    }

    public function testView(): void
    {
        $this->assertInstanceOf(
            View::class,
            $this->controller->getView()
        );
    }

    public function testSet(): void
    {
        $this->assertEquals(
            $this->controller,
            $this->controller->set('test', 'value')
        );

        $this->assertEquals(
            [
                'test' => 'value'
            ],
            $this->controller->getData()
        );
    }

    public function testSetData(): void
    {
        $this->assertEquals(
            $this->controller,
            $this->controller->setData([
                'test' => 'value'
                
            ])
        );

        $this->assertEquals(
            [
                'test' => 'value'
            ],
            $this->controller->getData()
        );
    }

    public function testRender(): void
    {
        $this->assertEquals(
            $this->controller,
            $this->controller->render('test')
        );

        $this->assertEquals(
            'Test',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testRenderAppends(): void
    {
        $this->controller->render('test');
        $this->controller->render('test');

        $this->assertEquals(
            'TestTest',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testInvoke(): void
    {
        $this->assertEquals(
            $this->controller,
            $this->controller->invokeAction('test')
        );
    }

    public function testInvoiceArgs(): void
    {
        $this->assertEquals(
            $this->controller,
            $this->controller->invokeAction('arg', ['test'])
        );
    }

    public function testInvokeResponse(): void
    {
        $response = $this->controller->getResponse();

        $this->controller->invokeAction('redirect');

        $this->assertNotEquals(
            $response,
            $this->controller->getResponse()
        );
    }

    public function testInvokeBase(): void
    {
        $this->expectException(RuntimeException::class);

        $this->controller->invokeAction('getData');
    }

    public function testInvokeProtected(): void
    {
        $this->expectException(RuntimeException::class);

        $this->controller->invokeAction('protected');
    }

    public function testInvokeInvalid(): void
    {
        $this->expectException(RuntimeException::class);

        $this->controller->invokeAction('invalid');
    }

    protected function setUp(): void
    {
        $request = new ServerRequest();
        $response = new ClientResponse();

        $this->controller = new MockController($request, $response);
    }

    public static function setUpBeforeClass(): void
    {
        View::addPath('tests/templates');
    }

}
