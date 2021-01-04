<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function index()
    {
        $search = request('name');

        // % means start with $search char and then anything can come after it.
        return User::where('name', 'LIKE', "%$search%")
            ->take(5)
            ->pluck('name');
    }
}
