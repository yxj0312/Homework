<?php

namespace App\Http\Controllers\Api;

use App\Channel;
use App\Http\Controllers\Controller;

class ChannelsController extends Controller
{
    /**
     * Fetch all channels.
     */
    public function index()
    {
        return cache()->rememberForever('channels', function () {
            // return Channel::where('archived', false)->orderBy('name', 'desc')->get();
            // remove query, cause we add a global scope in channel model
            // remove orderBy, cause we move them also to the global scope
            return Channel::all();
        });
    }
}
