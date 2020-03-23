<?php
/**
 * @category Application
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\User;

use Helper;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\SupportFacades\Log;

class StoreHandler
{
    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $api = false, $redirect = null)
    {
        // current timestamp
        $current_time = date('Y-m-d H:i:s', time());

        // $rules is set of validation rules
        $rules = [
        	'first_name' => 'required',
        	'second_name' => 'required',
        	'last_name' => 'required',
        	'username' => 'required',
        	'email' => 'required|string|email|max:255|unique:users,email',
        	'phone' => 'required',
        	'password' => 'required|string|min:8|confirmed',
        	'role' => 'required',
        ];

        // validate request data before continue
        $validator = Validator::make($request->all(), $rules);

        // if request data is valid it continue 
        // if is invalid return back with errors
        if ($validator->fails()) { 
            if($request->ajax() || $api) {
                return response()->json(['errors' => $validator->errors()]);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        } 
        $save_uploaded_profile_url = Helper::save_uploaded_file($request, 'profile_url');
        
        // $body is the array of data to be save to user table 
        $body = [
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
        	'profile_url' => $save_uploaded_profile_url,
        	'password' => bcrypt($request->password),
            'role' => $request->role,
        	'remember_token' => ($request->remember_token)? $request->remember_token: "",
        	'status' => ($request->status)? $request->status: 1,
            'created_at' => $current_time,
            'updated_at' => $current_time,
            'deleted_at' => $request->deleted_at,
        ];

        // saving the $body data to user table
        $model = User::create($body);

        if($request->ajax() || $api) {
            $user_default = [ 
                ['key'=>'', 'value'=>'Select user'], 
                ['key'=>'<new>', 'value'=>'Create new user'], 
            ];
            $user_new = User::orderBy('first_name', 'ASC')->get(['null as key','first_name as value'])->toArray();
            $options_list = (array) array_merge($user_default, $user_new);
                
            return response()->json([
                "status"=>"success",
                "options_list"=> $options_list,
                "selected_id"=>$model->null
            ]);
        }

        $redirect = ($redirect)? $redirect: request('redirect');
        if($redirect) {
            return redirect($redirect)->with(['alert-success'=>'User data saved']);
        }
        
        // redirect to the edit page with success massage
        return redirect('admin/user/edit/'.$model->null)->with(['alert-success'=>'User data saved']);
    }
}