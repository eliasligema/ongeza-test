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

class IndexTableHandler
{
    /**
     * Display a listing of the User.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function handler(Request $request, $api = false)
    {
        // initialize data to send to the view or client
        $data = [];

        // $limit is the number of items per page (in pagination)
        $limit = (int) ($request->limit)? (int) $request->limit: 10;

        // $search is searched value from user interface
        $search = request('__search');
        
        // initialize User model
        $model = (new User)->newQuery();
        
        // check if user request for search
        if($search){
            $search = str_replace(' ', '%', $search);
            // this do the margic for search in $model
            $model->where(function($query) use($search){
                $query->where('id', 'LIKE', "%".$search."%") // match Id column
                    ->orWhere('first_name', 'LIKE', "%".$search."%") // match First Name column
                    ->orWhere('second_name', 'LIKE', "%".$search."%") // match Second Name column
                    ->orWhere('last_name', 'LIKE', "%".$search."%") // match Last Name column
                    ->orWhere('username', 'LIKE', "%".$search."%") // match Username column
                    ->orWhere('email', 'LIKE', "%".$search."%") // match Email column
                    ->orWhere('phone', 'LIKE', "%".$search."%") // match Phone column
                    ->orWhere('profile_url', 'LIKE', "%".$search."%") // match Profile Url column
                    ->orWhere('password', 'LIKE', "%".$search."%") // match Password column
                    ->orWhere('role', 'LIKE', "%".$search."%") // match Role column
                    ->orWhere('remember_token', 'LIKE', "%".$search."%") // match Remember Token column
                    ->orWhere('status', 'LIKE', "%".$search."%") // match Status column
                    ->orWhere('created_at', 'LIKE', "%".$search."%") // match Created Time column
                    ->orWhere('updated_at', 'LIKE', "%".$search."%") // match Updated Time column
                    ->orWhere('deleted_at', 'LIKE', "%".$search."%"); // match Deleted Time column
            });
        }
        
        // assign model values to $data
        $paginate_list = (object) $model->paginate($limit);

        if($paginate_list->count() == 0) {
            $request->merge(['page' => 1]);
            $paginate_list = (object) $model->paginate($limit);
        }

        $data['users_list'] = $paginate_list;

        // if $api is true return the json data
        if($api) {
            return response()->json($data);
        }

        // if $api is false return the view
        return view('admin.user.index-table', $data);
    }
}