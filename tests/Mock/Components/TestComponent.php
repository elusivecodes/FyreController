<?php
declare(strict_types=1);

namespace Tests\Mock\Components;

use
    Fyre\Controller\Component;

class TestComponent extends Component
{

    public function value(): int
    {
        return 1;
    }

}
