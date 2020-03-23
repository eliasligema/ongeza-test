<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Customer;

use Helper;
use Validator;
use App\Models\Customer;
use Illuminate\Http\Request;

class UpdateHandler
{
    /**
     * Update the specified Customer in storage.
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
        $model = Customer::where('id', $id)->first();

        // initialize $body: data that need to be updated
        $body = [];
        if(isset($inputs->first_name)) $body['first_name'] = $inputs->first_name; // check if field 'first_name' exist from the request and then add it to $body
        if(isset($inputs->last_name)) $body['last_name'] = $inputs->last_name; // check if field 'last_name' exist from the request and then add it to $body
        if(isset($inputs->town_name)) $body['town_name'] = $inputs->town_name; // check if field 'town_name' exist from the request and then add it to $body
        if(isset($inputs->gender_id)) $body['gender_id'] = $inputs->gender_id; // check if field 'gender_id' exist from the request and then add it to $body
        if(isset($inputs->is_deleted)) $body['is_deleted'] = ($inputs->is_deleted == 'on')? 1: 0; // check if field 'is_deleted' exist from the request and then add it to $body
        
        // if there is data to update then update
        if(count($body)){
            // update customer table
            $model->update($body);
        }

        if($request->ajax() || $api) {
            $customer_default = [ 
                ['id'=>'', 'name'=>'Select customer'], 
                ['id'=>'<new>', 'name'=>'Create new customer'], 
            ];
            $customer_new = Customer::orderBy('first_name', 'ASC')->get(['null as id','first_name as name'])->toArray();
            $customer_list = (array) array_merge($customer_default, $customer_new);
                
            return response()->json([
                "status"=>"success",
                "customer_list"=> $customer_list,
                "selected_id"=>$model->null
            ]);
        }

        $redirect = ($redirect)? $redirect: request('redirect');
        if($redirect) {
            return redirect($redirect)->with(['alert-success'=>'Customer data updated']);
        }
        
        // redirect to the edit page with success massage
        return redirect()->back()->with(['alert-success'=>'Customer data updated']);
    }
}