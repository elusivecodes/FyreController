<?php
declare(strict_types=1);

namespace Fyre\Controller;

use
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    Fyre\View\View,
    ReflectionClass,
    ReflectionException,
    ReflectionMethod,
    RuntimeException;

/**
 * Controller
 */
abstract class Controller
{

    protected ServerRequest $request;

    protected ClientResponse $response;

    protected View $view;

    /**
     * New Controller constructor.
     * @param ServerRequest $request The ServerRequest.
     * @param ClientResponse $response The ClientResponse.
     */
    public function __construct(ServerRequest $request, ClientResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = new View();
    }

    /**
     * Get the view data.
     * @return array The view data.
     */
    public function getData(): array
    {
        return $this->view->getData();
    }

    /**
     * Get the ServerRequest.
     * @return ServerServerRequest The request.
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * Get the ClientResponse.
     * @return ClientClientResponse The response.
     */
    public function getResponse(): ClientResponse
    {
        return $this->response;
    }

    /**
     * Get the View.
     * @return View The View.
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * Invoke a public action.
     * @param string $action The controller method.
     * @param array $args The arguments.
     * @return Controller The Controller.
     * @throws RuntimeException if the action is not accessible.
     */
    public function invokeAction(string $action, array $args = []): static
    {
        if (!$this->isAccessible($action)) {
            throw new RuntimeException('Invalid method invocation: '.$action);
        }

        $response = $this->$action(...$args);

        if ($response && $response instanceof ClientResponse) {
            $this->response = $response;
        }

        return $this;
    }

    /**
     * Render a view template and append the output to the response body.
     * @param string $template The template.
     * @return Controller The Controller.
     */
    public function render(string $template): static
    {
        $output = $this->view->render($template);

        $this->response->appendBody($output);

        return $this;
    }

    /**
     * Set a view data value.
     * @param string $key The key.
     * @param mixed $value The value.
     * @return Controller The Controller.
     */
    public function set(string $key, $value): static
    {
        return $this->setData([$key => $value]);
    }

    /**
     * Set the view data.
     * @param array $data The data.
     * @return Controller The Controller.
     */
    public function setData(array $data): static
    {
        $this->view->setData($data);

        return $this;
    }

    /**
     * Determine if a action is accessible.
     * @param string $action The action.
     * @return bool TRUE if the action is accessible, otherwise FALSE.
     */
    protected function isAccessible(string $action): bool
    {
        $controller = new ReflectionClass(self::class);

        if ($controller->hasMethod($action)) {
            return false;
        }

        try {
            $method = new ReflectionMethod($this, $action);
        } catch (ReflectionException $e) {
            return false;
        }

        return $method->isPublic() && $method->getName() === $action;
    }

}
