<?php
declare(strict_types=1);

namespace Tests\Mock;

use Fyre\Controller\Controller;
use Fyre\Server\RedirectResponse;

class MockController extends Controller
{

    public function test()
    {

    }

    public function arg(string $arg)
    {

    }

    public function redirect()
    {
        return new RedirectResponse('https://test.com/');
    }

    protected function protected()
    {

    }

    private function private()
    {

    }

}
