<?php
declare(strict_types=1);

namespace Tests\Mock;

use Fyre\Controller\Controller;
use Fyre\Server\RedirectResponse;

class MockController extends Controller
{

    public function test(): void
    {

    }

    public function arg(string $arg): void
    {

    }

    public function redirect(): RedirectResponse
    {
        return new RedirectResponse('https://test.com/');
    }

    protected function protected(): void
    {

    }

    private function private(): void
    {

    }

}
