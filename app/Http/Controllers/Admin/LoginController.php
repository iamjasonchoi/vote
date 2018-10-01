<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Validator;

class LoginController extends Controller
{
    
    public function index(Request $request)
    {
    	if ($request->isMethod('get')) {
            return view('admin.login');
        } elseif ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                    'username' => 'required|min:2|max:16',
                    'password' => 'required'
                ]);

            if ($validator->fails()) {
            	return redirect(route('admin.login'))
            		->withErrors($validator)
            		->withInput();
            }
            
            if (Auth::attempt([
            	'username' => $request->username,
            	'password' => $request->password,
            ])) {
            	return redirect(route('admin.index.index'));
            } else {
 				return back()->withErrors('用户名或密码错误');
            }
        }
    }
}
