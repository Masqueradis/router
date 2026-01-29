<?php

declare(strict_types=1);

namespace Masqueradis\Tests\FakeControllers;

use Masqueradis\Attributes\Route;
use Masqueradis\Routers\Request;

class FakeParamController
{
    #[Route('/paramtest')]
    public function paramtest($id, $any)
    {
        printf('Success');
    }

}