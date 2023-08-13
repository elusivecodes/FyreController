<?php
declare(strict_types=1);

namespace Fyre\Controller\Exceptions;

use RuntimeException;

/**
 * ControllerException
 */
class ControllerException extends RuntimeException
{

    public static function forInvalidComponent(string $name): static
    {
        return new static('Component not found: '.$name);
    }

    public static function forInvalidMethodInvocation(string $action): static
    {
        return new static('Invalid method invocation: '.$action);
    }

    public static function forUnloadedComponent(string $name): static
    {
        return new static('Component not loaded: '.$name);
    }

}
