<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    /**
     * Or write it in the route/web.php
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');   
    // }
    /**
     * Store a new user avatar.
     */
    public function store()
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            // store() will store with hash filename; storeAs() can save a special name
            /* 'avatar_path' => request()->file('avatar')->storeAs('avatars', 'avatar.jpg', 'public') */        
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
