<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Controller\ComponentRegistry;
use Fyre\Controller\Controller;
use Fyre\Controller\Exceptions\ControllerException;
use Fyre\ORM\Model;
use Fyre\Server\ClientResponse;
use Fyre\Server\ServerRequest;
use Fyre\View\Template;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;
use Tests\Mock\MockController;
use Tests\Mock\TestTitle2Controller;

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

    public function testTitle(): void
    {
        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new TestTitle2Controller($request, $response);

        $controller->getView()->setLayout(null);
        $controller->invokeAction('test');

        $this->assertSame(
            'Test Title 2',
            $controller->getResponse()->getBody()
        );
    }

    public function testFetchModel(): void
    {
        $model = $this->controller->fetchModel();

        $this->assertInstanceOf(
            Model::class,
            $model
        );

        $this->assertSame(
            'Mock',
            $model->getAlias()
        );
    }

    public function testFetchModelWithAlias(): void
    {
        $model = $this->controller->fetchModel('Test');

        $this->assertInstanceOf(
            Model::class,
            $model
        );

        $this->assertSame(
            'Test',
            $model->getAlias()
        );
    }

    public function testGetName(): void
    {
        $this->assertSame(
            'Mock',
            $this->controller->getName()
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
                'title' => 'Mock',
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
                'title' => 'Mock',
                'test' => 'value'
            ],
            $this->controller->getData()
        );
    }

    public function testSetTemplateAutoRender(): void
    {
        $this->assertSame(
            $this->controller,
            $this->controller->setTemplate('Mock/other')
        );

        $this->assertSame(
            'Mock/other',
            $this->controller->getTemplate()
        );

        $this->controller->enableAutoRender();
        $this->controller->getView()->setLayout(null);
        $this->controller->invokeAction('test');

        $this->assertSame(
            'Other',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testRender(): void
    {
        $this->controller->getView()->setLayout(null);

        $this->assertSame(
            $this->controller,
            $this->controller->render('Mock/test')
        );

        $this->assertSame(
            'Test',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testRenderAppends(): void
    {
        $this->controller->getView()->setLayout(null);

        $this->controller->render('Mock/test');
        $this->controller->render('Mock/test');

        $this->assertSame(
            'TestTest',
            $this->controller->getResponse()->getBody()
        );
    }

    public function testInvoke(): void
    {
        $this->controller->enableAutoRender();
        $this->controller->getView()->setLayout(null);

        $this->assertSame(
            $this->controller,
            $this->controller->invokeAction('test')
        );

        $this->assertSame(
            'Test',
            $this->controller->getResponse()->getBody()
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
        $this->expectException(ControllerException::class);

        $this->controller->invokeAction('getData');
    }

    public function testInvokeProtected(): void
    {
        $this->expectException(ControllerException::class);

        $this->controller->invokeAction('protected');
    }

    public function testInvokeInvalid(): void
    {
        $this->expectException(ControllerException::class);

        $this->controller->invokeAction('invalid');
    }

    protected function setUp(): void
    {
        $request = new ServerRequest();
        $response = new ClientResponse();

        $this->controller = new MockController($request, $response);
        $this->controller->enableAutoRender(false);
    }

    public static function setUpBeforeClass(): void
    {
        ComponentRegistry::clear();
        ComponentRegistry::addNamespace('Tests\Mock\Components');
        Template::clear();
        Template::addPath('tests/templates');
    }

}
