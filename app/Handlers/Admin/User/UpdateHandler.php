<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\User;

use Helper;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateHandler
{
    /**
     * Update the specified User in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $id = null, $api = false, $redirect = null)
    {
        // current timestamp
        $current_time = date('Y-m-d H:i:s', time());

        // $inputs is object of all submited inputs
        $inputs = (object) $request->all();

        // initialize model and set filters
        $model = User::where('id', $id)->first();

        // initialize $body: data that need to be updated
        $body = [];
        if(isset($inputs->first_name)) $body['first_name'] = $inputs->first_name; // check if field 'first_name' exist from the request and then add it to $body
        if(isset($inputs->second_name)) $body['second_name'] = $inputs->second_name; // check if field 'second_name' exist from the request and then add it to $body
        if(isset($inputs->last_name)) $body['last_name'] = $inputs->last_name; // check if field 'last_name' exist from the request and then add it to $body
        if(isset($inputs->username)) $body['username'] = $inputs->username; // check if field 'username' exist from the request and then add it to $body
        if(isset($inputs->email)) $body['email'] = $inputs->email; // check if field 'email' exist from the request and then add it to $body
        if(isset($inputs->phone)) $body['phone'] = $inputs->phone; // check if field 'phone' exist from the request and then add it to $body
        $uploaded_profile_url = Helper::save_uploaded_file($request, 'profile_url');
        if(isset($uploaded_profile_url) && $uploaded_profile_url) $body['profile_url'] = $uploaded_profile_url; // check if field 'profile_url' exist from the request and then add it to $body
        if(isset($inputs->password)) $body['password'] = $inputs->password; // check if field 'password' exist from the request and then add it to $body
        if(isset($inputs->role)) $body['role'] = $inputs->role; // check if field 'role' exist from the request and then add it to $body
        if(isset($inputs->remember_token)) $body['remember_token'] = $inputs->remember_token; // check if field 'remember_token' exist from the request and then add it to $body
        if(isset($inputs->status)) $body['status'] = ($inputs->status)? $inputs->status: 0; // check if field 'status' exist from the request and then add it to $body
        if(isset($inputs->created_at)) $body['created_at'] = $inputs->created_at; // check if field 'created_at' exist from the request and then add it to $body
        $body['updated_at'] =  $current_time; // check if field 'updated_at' exist from the request and then add it to $body
        if(isset($inputs->deleted_at)) $body['deleted_at'] = $inputs->deleted_at; // check if field 'deleted_at' exist from the request and then add it to $body
        
        // if there is data to update then update
        if(count($body)){
            // update user table
            $model->update($body);
        }

        if($request->ajax() || $api) {
            $user_default = [ 
                ['id'=>'', 'name'=>'Select user'], 
                ['id'=>'<new>', 'name'=>'Create new user'], 
            ];
            $user_new = User::orderBy('first_name', 'ASC')->get(['null as id','first_name as name'])->toArray();
            $user_list = (array) array_merge($user_default, $user_new);
                
            return response()->json([
                "status"=>"success",
                "user_list"=> $user_list,
                "selected_id"=>$model->null
            ]);
        }

        $redirect = ($redirect)? $redirect: request('redirect');
        if($redirect) {
            return redirect($redirect)->with(['alert-success'=>'User data updated']);
        }
        
        // redirect to the edit page with success massage
        return redirect()->back()->with(['alert-success'=>'User data updated']);
    }
}