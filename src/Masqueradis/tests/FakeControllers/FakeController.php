<?php

declare(strict_types=1);

namespace Masqueradis\Tests\FakeControllers;

use Masqueradis\Attributes\Route;
use Masqueradis\Routers\Request;

#[Route('/admin')]
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