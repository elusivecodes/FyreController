# FyreController

**FyreController** is a free, controller library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Controller Creation](#controller-creation)
- [Methods](#methods)



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

- `$request` is a *ServerRequest*.
- `$response` is a *ClientResponse*.

```php
class MyController extends Controller {}

$controller = new MyController($request, $response);
```


## Methods

**Get Data**

Get the view data.

```php
$data = $controller->getData();
```

**Get Request**

Get the *ServerRequest*.

```php
$request = $controller->getRequest();
```

**Get Response**

Get the *ClientResponse*.

```php
$response = $controller->getResponse();
```

**Get View**

Get the *View*.

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

If the invoked method returns a *ClientResponse* it will be set on the controller.

**Render**

Render a view template and append the output to the response body.

- `$template` is a string representing the template file.

```php
$controller->render($template);
```

**Set**

Set a view data value.

- `$key` is a string representing the data key.
- `$value` is the value.

```php
$controller->set($key, $value);
```

**Set Data**

Set the view data.

- `$data` is an array containing data to pass to the template.

```php
$controller->setData($data);
```