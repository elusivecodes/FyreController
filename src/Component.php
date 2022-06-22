<?php
declare(strict_types=1);

namespace Fyre\Controller;

use function
    array_replace_recursive;

/**
 * Component
 */
abstract class Component
{

    protected static array $defaults = [];

    protected Controller $controller;

    protected array $config;

    /**
     * New Component constructor.
     * @param Controller $controller The Controller.
     * @param array $options The component options.
     */
    public function __construct(Controller $controller, array $options = [])
    {
        $this->controller = $controller;

        $this->config = array_replace_recursive(static::$defaults, $options);
    }

    /**
     * Get the component config.
     * @return array The component config.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get the Controller.
     * @return Controller The Controller.
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

}
