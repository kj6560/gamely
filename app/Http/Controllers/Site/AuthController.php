<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $user = Auth::user();
        
        if (!empty($user)) {
            return redirect()->route('home')->with('success','Logged In Already');
        }

        return view('site.login');
    }
    public function loginAuthentication(Request $request)
    {
       $data = $request->all();
        $user = User::where('number', $data['number'])->first();
        if(empty($user)){
            return redirect()->back()->with('error', 'User not found');
        }
        if(Auth::attempt(['number' => $data['number'], 'password' => $data['password']])){
            return redirect()->route('home');
        }else{
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        return redirect()->back();
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        
        if (!empty($user)) {
            return redirect()->route('home')->with('success','Please logout First');
        }
        return view('site.register');
    }
    public function registerUser(Request $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::where('number', $data['number'])->first();
        if ($user) {
            return redirect()->back()->with('error', 'User already exists');
        } else {
            $user = User::create($data);
            if (!empty($user->id)) {
                return redirect()->route('login')->with('success', 'Registered successfuly');
            } else {
                return redirect()->back()->with('error', 'Something went wrong');
            }
        }

        return redirect()->route('home');
    }
}
