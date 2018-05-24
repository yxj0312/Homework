<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

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
