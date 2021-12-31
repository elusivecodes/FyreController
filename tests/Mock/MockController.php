<?php
declare(strict_types=1);

namespace Tests\Mock;

use
    Fyre\Controller\Controller,
    Fyre\Server\ClientResponse;

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
        return (new ClientResponse())->redirect('https://test.com/');
    }

    protected function protected()
    {

    }

    private function private()
    {

    }

}
