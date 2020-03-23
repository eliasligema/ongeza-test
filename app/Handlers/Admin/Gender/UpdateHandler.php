<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Gender;

use Helper;
use Validator;
use App\Models\Gender;
use Illuminate\Http\Request;

class UpdateHandler
{
    /**
     * Update the specified Gender in storage.
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
        $model = Gender::where('id', $id)->first();

        // initialize $body: data that need to be updated
        $body = [];
        if(isset($inputs->gender_name)) $body['gender_name'] = $inputs->gender_name; // check if field 'gender_name' exist from the request and then add it to $body
        
        // if there is data to update then update
        if(count($body)){
            // update gender table
            $model->update($body);
        }

        if($request->ajax() || $api) {
            $gender_default = [ 
                ['id'=>'', 'name'=>'Select gender'], 
                ['id'=>'<new>', 'name'=>'Create new gender'], 
            ];
            $gender_new = Gender::orderBy('gender_name', 'ASC')->get(['id as id','gender_name as name'])->toArray();
            $gender_list = (array) array_merge($gender_default, $gender_new);
                
            return response()->json([
                "status"=>"success",
                "gender_list"=> $gender_list,
                "selected_id"=>$model->id
            ]);
        }

        $redirect = ($redirect)? $redirect: request('redirect');
        if($redirect) {
            return redirect($redirect)->with(['alert-success'=>'Gender data updated']);
        }
        
        // redirect to the edit page with success massage
        return redirect()->back()->with(['alert-success'=>'Gender data updated']);
    }
}