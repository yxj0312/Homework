<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            // dd(request()->all());
            $user = User::where('confirmation_token', request('token'))
                ->firstOrFail()
                ->confirm();
        } catch (\Exception $e) {
            return redirect(route('threads'))
                ->with('flash', 'Unknown token.');

        }
                
        /* $user->confirmed = true;
        $user->save(); */
        
        // Or you can add confirmed to fillable column and use:
        // ->update(['confirmed' => true]);

        return redirect(route('threads') )
            ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    }
}
