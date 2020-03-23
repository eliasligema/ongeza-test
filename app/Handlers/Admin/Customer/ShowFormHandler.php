<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Customer;

use App\Models\Gender;
use App\Models\Customer;
use Illuminate\Http\Request;

class ShowFormHandler
{
    /**
     * Display the specified Customer.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $id = null, $api = false, $redirect = null, $hidden = [])
    {
        // initialize data to send to the view or client
        $data = [];
        $data['is_namespace'] = null;
        $sub_page = request('sub_page')? request('sub_page'): 'form';

        // Set $hidden
        $data['hidden'] = $hidden;
        $data['gender'] = Gender::all();
        
        // Set $redirect
        $data['redirect'] = $redirect;

        // Get and assign all data from $customer to $data
        $data['model_info'] = Customer::where('id', $id)->first();

        

        if($sub_page === 'form' || $sub_page === 'edit') {
            $model_list = self::form($request, $id);

            $data['is_namespace'] = '';
            foreach ($model_list as $key => $value) {
                $data[$key] = $value;
            }
        }

        // render and send view to user
        $sub_view = 'show-form';
        if($sub_page == 'edit') {
            $sub_view = 'show-edit';
        }
        

        if($api) {
            return response()->json($data);
        }

        return view('admin.customer.'.$sub_view, $data);
    }
    
    /**
     * Display the specified Customer.
     *
     * @param  String  $id
     * @param  \App\Models\Customer  $customer
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function form(Request $request, $id = null, $redirect = null, $hidden = [])
    {
        // initialize data to send to the view or client
        $data = [];

        // return and send data to user
        return $data;
    }
}