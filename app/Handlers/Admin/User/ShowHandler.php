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

class ShowHandler
{
    /**
     * Display the specified User.
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

        // Get and assign all data from $user to $data
        $data['model_info'] = User::where('id', $id)->first();
        
        // Get and assign all data from User model to $data
        $data['model_list'] = User::get();

        if($api) {
            return new ShowFormHandler($request, $id, $api);
        }

        // render and send view to user
        return view('admin.user.show', $data);
    }
}