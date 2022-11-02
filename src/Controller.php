<?php
declare(strict_types=1);

namespace Fyre\Controller;

use
    Fyre\Controller\Exceptions\ControllerException,
    Fyre\ORM\Model,
    Fyre\ORM\ModelRegistry,
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    Fyre\Utility\Path,
    Fyre\View\View,
    ReflectionClass,
    ReflectionException,
    ReflectionMethod;

use function
    preg_replace,
    str_replace,
    substr;

/**
 * Controller
 */
abstract class Controller
{

    protected ServerRequest $request;

    protected ClientResponse $response;

    protected View $view;

    protected string|null $name = null;

    protected string|null $template = null;

    protected bool $autoRender = true;

    /**
     * New Controller constructor.
     * @param ServerRequest $request The ServerRequest.
     * @param ClientResponse $response The ClientResponse.
     */
    public function __construct(ServerRequest $request, ClientResponse $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->getView();
        $this->getName();

        $title = static::humanize($this->name);

        $this->set('title', $title);
    }

    /**
     * Enable or disable auto rendering.
     * @param bool Whether to enable or disable auto rendering.
     * @return Controller The Controller.
     */
    public function enableAutoRender(bool $autoRender = true): static
    {
        $this->autoRender = $autoRender;

        return $this;
    }

    /**
     * Fetch a Model from the ModelRegistry.
     * @param string|null $alias  The model name.
     * @return Model The Model.
     */
    public function fetchModel(string|null $alias  = null): Model
    {
        $alias ??= $this->getName();

        return ModelRegistry::use($alias );
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
     * Get the controller name.
     * @return string The controller name.
     */
    public function getName(): string
    {
        if ($this->name === null) {
            $reflection = new ReflectionClass($this);

            $controller = $reflection->getShortName();

            $this->name = substr($controller, 0, -10);
        }

        return $this->name;
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
     * Get the template.
     * @return string|null The template.
     */
    public function getTemplate(): string|null
    {
        return $this->template;
    }

    /**
     * Get the View.
     * @return View The View.
     */
    public function getView(): View
    {
        return $this->view ??= new View($this);
    }

    /**
     * Invoke a public action.
     * @param string $action The controller method.
     * @param array $args The arguments.
     * @return Controller The Controller.
     * @throws ControllerException if the action is not accessible.
     */
    public function invokeAction(string $action, array $args = []): static
    {
        if (!$this->isAccessible($action)) {
            throw ControllerException::forInvalidMethodInvocation($action);
        }

        $response = $this->$action(...$args);

        if ($response && $response instanceof ClientResponse) {
            $this->response = $response;
        } else if ($this->autoRender && !$this->response->getBody()) {
            $this->template ??= Path::join($this->getName(), $action);
            $this->render($this->template);
        }

        return $this;
    }

    /**
     * Load a Component.
     * @param string $name The component name.
     * @param array $options The component options.
     * @return View The View.
     */
    public function loadComponent(string $name, array $options = []): static
    {
        $this->$name = ComponentRegistry::load($name, $this, $options);

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
    public function set(string $key, mixed $value): static
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
     * Set the template file for auto rendering.
     * @param string $file The template file.
     * @return Controller The Controller.
     */
    public function setTemplate(string $file): static
    {
        $this->template = $file;

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

    /**
     * Humanize a string.
     * @param string $string The string.
     * @return string The humanized string.
     */
    protected static function humanize(string $string): string
    {
        $string = preg_replace('/(?<=[a-z0-9_])([A-Z0-9])/', ' \1', $string);

        return str_replace('_', '', $string);
    }

}
