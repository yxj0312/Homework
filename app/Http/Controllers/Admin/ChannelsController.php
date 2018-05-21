<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChannelsController extends Controller
{
    public function index()
    {
        $channels = Channel::with('threads')->get();

        return view('admin.channels.index', compact('channels'));
    }
}
