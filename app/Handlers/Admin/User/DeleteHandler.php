<?php
/**
 * @category Method handler
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */        
namespace App\Handlers\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeleteHandler
{
    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $id = null, $api = false)
    {
        $user = User::where('id', $id)->first();
        // Find profile_url file
        self::delete_profile_url($user);
        // Find User from user table and delete
        if($post) {
            $user->delete();
        }

        if($api) {
            return response()->json(['status'=>'success', 'message'=>'User data deleted']);
        }

        if(request('redirect')){
            return redirect(request('redirect'))->with(['alert-success'=>'User data deleted']);
        }

        // redirect to list page with success massage
        return redirect()->back()->with(['alert-success'=>'User data deleted']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function delete_profile_url(User $user)
    {
        if($user){
            $filename = public_path('uploaded/'.$user->profile_url);
            if(File::exists($filename)){
                File::delete($filename);
            }
        }
    }
}