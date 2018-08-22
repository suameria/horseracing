<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function top()
    {
        return redirect()->route('admin.login.index');
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home.index');
        }

        return view('admin.login.index');
    }

    public function authenticate(Request $request)
    {
        $rules = [
            'email'    => 'bail|email|required',
            'password' => 'bail|required|min:4'
        ];

        $this->validate($request, $rules);

        $authKey = [
            'email'    => $request->input('email'),
            'password' => $request->input('password')
        ];

        if(Auth::attempt($authKey)) {
            return redirect()->route('admin.home.index');
        }

        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login.index');
    }
}
