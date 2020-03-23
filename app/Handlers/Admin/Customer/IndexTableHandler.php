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

class IndexTableHandler
{
    /**
     * Display a listing of the Customer.
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
        
        // initialize Customer model
        $model = (new Customer)->newQuery();
        
        // check if user request for search
        if($search){
            $search = str_replace(' ', '%', $search);
            // this do the margic for search in $model
            $model->where(function($query) use($search){
                $query->where('id', 'LIKE', "%".$search."%") // match Id column
                    ->orWhere('first_name', 'LIKE', "%".$search."%") // match First Name column
                    ->orWhere('last_name', 'LIKE', "%".$search."%") // match Last Name column
                    ->orWhere('town_name', 'LIKE', "%".$search."%") // match Town Name column
                    ->orWhere('gender_id', 'LIKE', "%".$search."%") // match Gender column
                    ->orWhere('is_deleted', 'LIKE', "%".$search."%"); // match Is Deleted column
            });
        }
        
        // assign model values to $data
        $paginate_list = (object) $model->paginate($limit);

        if($paginate_list->count() == 0) {
            $request->merge(['page' => 1]);
            $paginate_list = (object) $model->paginate($limit);
        }

        $data['customers_list'] = $paginate_list;

        // if $api is true return the json data
        if($api) {
            return response()->json($data);
        }

        // if $api is false return the view
        return view('admin.customer.index-table', $data);
    }
}