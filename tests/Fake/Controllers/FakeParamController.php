<?php

declare(strict_types=1);

namespace Masqueradis\RouterTests\Fake\Controllers;

use Masqueradis\Router\Attributes\Route;

class FakeParamController
{
    #[Route('/paramtest')]
    public function paramtest($id, $any)
    {
        printf('Success');
    }
}
