<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;

class EditHandler
{
    /**
     * Show the form for editing the specified Customer.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $id = null, $api = false)
    {

        // initialize data to send to the view or client
        $data = [];

        $data['customer'] = new Customer();

        // Get and assign all data from $Customer to $data
        $data['model_info'] = Customer::where('id', $id)->first();

        // render and send view to user
        return view('admin.customer.edit', $data);
    }
}