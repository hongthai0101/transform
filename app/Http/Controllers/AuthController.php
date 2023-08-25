<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class AuthController extends Controller
{
    public function password()
    {
        return view('auth.profile');
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);
        $user->password = bcrypt($request->password);
        $user->save();
        return Redirect::route('auth.change-password')->with('success', 'Password changed successfully.');
    }
}
