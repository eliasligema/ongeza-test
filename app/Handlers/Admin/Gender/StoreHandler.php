<?php
/**
 * @category Application
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Gender;

use Helper;
use Validator;
use App\Models\Gender;
use Illuminate\Http\Request;

class StoreHandler
{
    /**
     * Store a newly created Gender in storage.
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
        	'gender_name' => 'required',
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
        
        // $body is the array of data to be save to gender table 
        $body = [
            'gender_name' => $request->gender_name,
        ];

        // saving the $body data to gender table
        $model = Gender::create($body);

        if($request->ajax() || $api) {
            $gender_default = [ 
                ['key'=>'', 'value'=>'Select gender'], 
                ['key'=>'<new>', 'value'=>'Create new gender'], 
            ];
            $gender_new = Gender::orderBy('gender_name', 'ASC')->get(['id as key','gender_name as value'])->toArray();
            $options_list = (array) array_merge($gender_default, $gender_new);
                
            return response()->json([
                "status"=>"success",
                "options_list"=> $options_list,
                "selected_id"=>$model->id
            ]);
        }

        $redirect = ($redirect)? $redirect: request('redirect');
        if($redirect) {
            return redirect($redirect)->with(['alert-success'=>'Gender data saved']);
        }
        
        // redirect to the edit page with success massage
        return redirect('admin/gender/edit/'.$model->id)->with(['alert-success'=>'Gender data saved']);
    }
}