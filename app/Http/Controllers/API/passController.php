<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\pass;

class passController extends Controller
{
    public function getAllPass()
    {
        return response()->json(['allPass' => pass::all()]);
    }

    public function index(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'FIO' => 'required',
            'email' => 'required'
        ]);
        if ($validate->fails()){
            return response()->json(['status' => 'error','errors' => $validate->errors()]);
        }

        $pass = new pass();
        $pass->FIO = $request['FIO'];
        $pass->email = $request['email'];
        $pass->dataStart = $request['dataStart'];
        $pass->dataEnd = $request['dataEnd'];
        $pass->purpose = $request['purpose'];
        $pass->save();

        return response()->json(['status' => 'success','input' => $request->all()]);
    }
}
