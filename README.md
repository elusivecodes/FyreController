# FyreController

**FyreController** is a free, open-source controller library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Controller Creation](#controller-creation)
- [Methods](#methods)
- [Component Registry](#component-registry)
- [Components](#components)



## Installation

**Using Composer**

```
composer require fyre/controller
```

In PHP:

```php
use Fyre\Controller\Controller;
```


## Controller Creation

- `$request` is a [*ServerRequest*](https://github.com/elusivecodes/FyreServer#server-requests).
- `$response` is a [*ClientResponse*](https://github.com/elusivecodes/FyreServer#client-responses).

```php
class MyController extends Controller {}

$controller = new MyController($request, $response);
```


## Methods

**Enable Auto Render**

Enable or disable auto rendering.

- `$autoRender` is a boolean indicating whether to enable auto rendering, and will default to *true*.

```php
$controller->enableAutoRender($autoRender);
```

**Fetch Model**

Fetch a [*Model*](https://github.com/elusivecodes/FyreORM#models) from the [*ModelRegistry*](https://github.com/elusivecodes/FyreORM).

- `$alias` is a string representing the model name, and will default to the controller name.

```php
$model = $controller->fetchModel($alias);
```

**Get Data**

Get the view data.

```php
$data = $controller->getData();
```

**Get Name**

Get the controller name.

```php
$name = $controller->getName();
```

**Get Request**

Get the [*ServerRequest*](https://github.com/elusivecodes/FyreServer#server-requests).

```php
$request = $controller->getRequest();
```

**Get Response**

Get the [*ClientResponse*](https://github.com/elusivecodes/FyreServer#client-responses).

```php
$response = $controller->getResponse();
```

**Get Template**

Get the template.

```php
$template = $controller->getTemplate();
```

**Get View**

Get the [*View*](https://github.com/elusivecodes/FyreView).

```php
$view = $controller->getView();
```

**Invoke Action**

Invoke a public action.

- `$action` is a string representing the controller method.
- `$args` is an array containing arguments to pass to the controller method.

```php
$controller->invokeAction($action, $args);
```

If the invoked method returns a [*ClientResponse*](https://github.com/elusivecodes/FyreServer#client-responses) it will be set on the controller.

If auto rendering is enabled, and no response is set, then the template will be rendered as the response.

**Load Component**

Load a [*Component*](#components).

- `$name` is a string representing the component name.
- `$options` is an array containing component options.

```php
$component = $controller->loadComponent($name, $options);
```

**Render**

Render a [*View*](https://github.com/elusivecodes/FyreView) template and append the output to the response body.

- `$template` is a string representing the template file.

```php
$controller->render($template);
```

**Set**

Set a [*View*](https://github.com/elusivecodes/FyreView) data value.

- `$key` is a string representing the data key.
- `$value` is the value.

```php
$controller->set($key, $value);
```

**Set Data**

Set the [*View*](https://github.com/elusivecodes/FyreView) data.

- `$data` is an array containing data to pass to the template.

```php
$controller->setData($data);
```

**Set Template**

Set the template file for auto rendering.

- `$file` is a string representing the template file.

```php
$controller->setTemplate($file);
```


## Component Registry

```php
use Fyre\Controller\ComponentRegistry;
```

**Add Namespace**

Add a namespace for automatically loading components.

- `$namespace` is a string representing the namespace.

```php
ComponentRegistry::addNamespace($namespace);
```

**Clear**

Clear all namespaces and components.

```php
ComponentRegistry::clear();
```

**Find**

Find a component class.

- `$name` is a string representing the component name.

```php
$className = ComponentRegistry::find($name);
```

**Get Namespaces**

Get the namespaces.

```php
$namespaces = ComponentRegistry::getNamespaces();
```

**Has Namespace**

Check if a namespace exists.

- `$namespace` is a string representing the namespace.

```php
$hasNamespace = ComponentRegistry::hasNamespace($namespace);
```

**Load**

Load a component.

- `$name` is a string representing the component name.
- `$controller` is a *Controller*.

```php
$component = ComponentRegistry::load($name, $controller);
```

**Remove Namespace**

Remove a namespace.

- `$namespace` is a string representing the namespace.

```php
$removed = ComponentRegistry::removeNamespace($namespace);
```

## Components

Components must be loaded using the `loadComponent` method of the *Controller*, and then can be accessed using the class name as a property of `$this`.

```php
$component = $this->MyComponent;
```

Custom components can be created by extending `\Fyre\Controller\Component`, suffixing the class name with "*Component*", and ensuring the `__construct` method accepts *Controller* as the argument.

**Get Config**

Get the configuration options.

```php
$config = $component->getConfig();
```

**Get Controller**

Get the *Controller*.

```php
$controller = $component->getController();
```