<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Validator;

class LoginController extends Controller
{
 
    /**
     * 后台登录模块
     * @author leekachung <[leekachung17@gmail.com]>
     * @DateTime         2018-10-02T01:18:58+0800
     * @return
     */
    public function index(Request $request)
    {
        //判断请求方式
    	if ($request->isMethod('get')) {
            return view('admin.login');
        } elseif ($request->isMethod('post')) {
            //数据验证
            $validator = Validator::make($request->all(), [
                    'username' => 'required|min:2|max:16',
                    'password' => 'required'
                ]);

            if ($validator->fails()) {
            	return redirect(route('admin.login'))
            		->withErrors($validator)
            		->withInput();
            }
            
            //用户认证
            if (Auth::attempt([
            	'username' => $request->username,
            	'password' => $request->password,
            ])) {
                session([
                    'status' => "欢迎回来, {$request->username}"
                ]);
            	return redirect(route('admin.index.index'));
            } else {
 				return back()->withErrors('用户名或密码错误');
            }
        }
    }
}
