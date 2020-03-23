<?php
/**
 * @category Application
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Customer;

use Helper;
use Validator;
use App\Models\Customer;
use Illuminate\Http\Request;

class StoreHandler
{
    /**
     * Store a newly created Customer in storage.
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
        	'last_name' => 'required',
        	'town_name' => 'required',
        	'gender_id' => 'required',
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
        
        // $body is the array of data to be save to customer table 
        $body = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'town_name' => $request->town_name,
            'gender_id' => $request->gender_id,
        	'is_deleted' => ($request->is_deleted == 'on')? 1: 0,
        ];

        // saving the $body data to customer table
        $model = Customer::create($body);

        if($request->ajax() || $api) {
            $customer_default = [ 
                ['key'=>'', 'value'=>'Select customer'], 
                ['key'=>'<new>', 'value'=>'Create new customer'], 
            ];
            $customer_new = Customer::orderBy('first_name', 'ASC')->get(['null as key','first_name as value'])->toArray();
            $options_list = (array) array_merge($customer_default, $customer_new);
                
            return response()->json([
                "status"=>"success",
                "options_list"=> $options_list,
                "selected_id"=>$model->null
            ]);
        }

        $redirect = ($redirect)? $redirect: request('redirect');
        if($redirect) {
            return redirect($redirect)->with(['alert-success'=>'Customer data saved']);
        }
        
        // redirect to the edit page with success massage
        return redirect('admin/customer/edit/'.$model->null)->with(['alert-success'=>'Customer data saved']);
    }
}