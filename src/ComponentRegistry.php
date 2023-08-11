<?php
declare(strict_types=1);

namespace Fyre\Controller;

use Fyre\Controller\Exceptions\ControllerException;

use function array_splice;
use function class_exists;
use function in_array;
use function is_subclass_of;
use function trim;

abstract class ComponentRegistry
{

    protected static array $namespaces = [];

    protected static array $components = [];

    /**
     * Add a namespace for loading components.
     * @param string $namespace The namespace.
     */
    public static function addNamespace(string $namespace): void
    {
        $namespace = static::normalizeNamespace($namespace);

        if (!in_array($namespace, static::$namespaces)) {
            static::$namespaces[] = $namespace;
        }
    }

    /**
     * Clear all namespaces and components.
     */
    public static function clear(): void
    {
        static::$namespaces = [];
        static::$components = [];
    }

    /**
     * Find a component class.
     * @param string $name The component name.
     * @return string|null The component class.
     */
    public static function find(string $name): string|null
    {
        return static::$components[$name] ??= static::locate($name);
    }

    /**
     * Get the namespaces.
     * @return array The namespaces.
     */
    public static function getNamespaces(): array
    {
        return static::$namespaces;
    }

    /**
     * Determine if a namespace exists.
     * @param string $namespace The namespace.
     * @return bool TRUE if the namespace exists, otherwise FALSE.
     */
    public static function hasNamespace(string $namespace): bool
    {
        $namespace = static::normalizeNamespace($namespace);

        return in_array($namespace, static::$namespaces);
    }

    /**
     * Load a component.
     * @param string $name The component name.
     * @param Controller $controller The Controller.
     * @param array $options The component options.
     * @return Component The Component.
     * @throws ControllerException if the component does not exist.
     */
    public static function load(string $name, Controller $controller, array $options = []): Component
    {
        $className = static::find($name);

        if (!$className) {
            throw ControllerException::forInvalidComponent($name);
        }

        return new $className($controller, $options);
    }

    /**
     * Remove a namespace.
     * @param string $namespace The namespace.
     * @return bool TRUE If the namespace was removed, otherwise FALSE.
     */
    public static function removeNamespace(string $namespace): bool
    {
        $namespace = static::normalizeNamespace($namespace);

        foreach (static::$namespaces AS $i => $otherNamespace) {
            if ($otherNamespace !== $namespace) {
                continue;
            }

            array_splice(static::$namespaces, $i, 1);

            return true;
        }

        return false;
    }

    /**
     * Locate a component class.
     * @param string $name The component name.
     * @return string|null The component class.
     */
    protected static function locate(string $name): string|null
    {
        foreach (static::$namespaces AS $namespace) {
            $className = $namespace.$name.'Component';

            if (class_exists($className) && is_subclass_of($className, Component::class)) {
                return $className;
            }
        }

        return null;
    }

    /**
     * Normalize a namespace
     * @param string $namespace The namespace.
     * @return string The normalized namespace.
     */
    protected static function normalizeNamespace(string $namespace): string
    {
        $namespace = trim($namespace, '\\');

        return $namespace ?
            '\\'.$namespace.'\\' :
            '\\';
    }

}
