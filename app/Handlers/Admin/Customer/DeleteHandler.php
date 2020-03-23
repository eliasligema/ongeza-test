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
use Illuminate\Support\Facades\File;

class DeleteHandler
{
    /**
     * Remove the specified Customer from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $id = null, $api = false)
    {
        $customer = Customer::where('id', $id)->first();
        // Find Customer from customer table and delete
        if($post) {
            $customer->delete();
        }

        if($api) {
            return response()->json(['status'=>'success', 'message'=>'Customer data deleted']);
        }

        if(request('redirect')){
            return redirect(request('redirect'))->with(['alert-success'=>'Customer data deleted']);
        }

        // redirect to list page with success massage
        return redirect()->back()->with(['alert-success'=>'Customer data deleted']);
    }
}