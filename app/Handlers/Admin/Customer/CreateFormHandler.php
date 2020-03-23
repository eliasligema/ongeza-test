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

class CreateFormHandler
{
    /**
     * Show the form for creating a new Customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $api = false, $redirect = null, $hidden = [])
    {
        // initialize data to send to the view or client
        $data = [];

        // Set $redirect
        $data['gender'] = Gender::all();
        $data['redirect'] = $redirect;
        
        // Set $hidden field and value
        foreach($hidden as $key => $value){
            $data[$key] = $value;
            $data['hidden'][] = $key;
        }

        if($api) {
            return response()->json($data);
        }

        // render and send view to user
        return view('admin.customer.create-form', $data);
    }
}