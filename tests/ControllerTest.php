<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Controller\Controller,
    Fyre\Error\Exceptions\Exception,
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
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
        $this->assertSame(
            $this->controller,
            $this->controller->set('test', 'value')
        );

        $this->assertSame(
            [
                'test' => 'value'
            ],
            $this->controller->getData()
        );
    }

    public function testSetData(): void
    {
        $this->assertSame(
            $this->controller,
            $this->controller->setData([
                'test' => 'value'
                
            ])
        );

        $this->assertSame(
            [
                'test' => 'value'
            ],
            $this->controller->getData()
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            $this->controller,
            $this->controller->render('test')
        );

        $this->assertSame(
            'Test',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testRenderAppends(): void
    {
        $this->controller->render('test');
        $this->controller->render('test');

        $this->assertSame(
            'TestTest',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testInvoke(): void
    {
        $this->assertSame(
            $this->controller,
            $this->controller->invokeAction('test')
        );
    }

    public function testInvoiceArgs(): void
    {
        $this->assertSame(
            $this->controller,
            $this->controller->invokeAction('arg', ['test'])
        );
    }

    public function testInvokeResponse(): void
    {
        $response = $this->controller->getResponse();

        $this->controller->invokeAction('redirect');

        $this->assertNotSame(
            $response,
            $this->controller->getResponse()
        );
    }

    public function testInvokeBase(): void
    {
        $this->expectException(Exception::class);

        $this->controller->invokeAction('getData');
    }

    public function testInvokeProtected(): void
    {
        $this->expectException(Exception::class);

        $this->controller->invokeAction('protected');
    }

    public function testInvokeInvalid(): void
    {
        $this->expectException(Exception::class);

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
