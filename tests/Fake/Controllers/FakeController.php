<?php

declare(strict_types=1);

namespace Masqueradis\RouterTests\Fake\Controllers;

use Masqueradis\Router\Attributes\Route;
use Masqueradis\Router\Routers\Request;

class FakeController
{
    #[Route('/dashboard', 'POST')]
    public function dashboard(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title', default: 'no title');
        printf('Admin with id: %s and title: %s.', $id, $title);
    }
}
