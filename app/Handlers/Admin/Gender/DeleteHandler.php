<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\Gender;

use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeleteHandler
{
    /**
     * Remove the specified Gender from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $id = null, $api = false)
    {
        $gender = Gender::where('id', $id)->first();
        // Find Gender from gender table and delete
        if($post) {
            $gender->delete();
        }

        if($api) {
            return response()->json(['status'=>'success', 'message'=>'Gender data deleted']);
        }

        if(request('redirect')){
            return redirect(request('redirect'))->with(['alert-success'=>'Gender data deleted']);
        }

        // redirect to list page with success massage
        return redirect()->back()->with(['alert-success'=>'Gender data deleted']);
    }
}