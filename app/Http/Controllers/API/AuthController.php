<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $User = new User();
        $User->name = $request['login'];
        $User->password = Hash::make($request['password']);
        $User->api_token = Str::random(60);
        $User->account_status = 'operator';
        $User->save();
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only(['name','password']))){
            return response()->json(['status' => 'error']);
        }
        return response()->json(['status' => 'success','account_status' => Auth::user()->getAccountStatus()]);
    }
}
