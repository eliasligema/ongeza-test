<?php
/**
 * App is un application where user can upload pictures 
 * for printing and will be deliverd to there location
 *
 * PHP version 7
 *
 * @category Application
 * @package  App
 * @author   Elias Ligema <eliasligema@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 */        
namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;

use App\Handlers\Admin\Customer\IndexHandler as CustomerIndexHandler;
use App\Handlers\Admin\Customer\CreateHandler as CustomerCreateHandler;
use App\Handlers\Admin\Customer\StoreHandler as CustomerStoreHandler;
use App\Handlers\Admin\Customer\ShowHandler as CustomerShowHandler;
use App\Handlers\Admin\Customer\EditHandler as CustomerEditHandler;
use App\Handlers\Admin\Customer\UpdateHandler as CustomerUpdateHandler;
use App\Handlers\Admin\Customer\DeleteHandler as CustomerDeleteHandler;

class CustomerController extends \App\Http\Controllers\Controller
{
    /**
     * This function run first automatic before any other function
     */
    public function __construct() {
        parent::__construct();
        // Run middleware for Customer controller
        // $this->middleware(['auth']);
    }

    /**
     * Display a listing of the Customer.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CustomerIndexHandler::handler($request, $this->api);
    }

    /**
     * Display a listing of the Customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function api_index(Request $request)
    {
        // get data from index method on this class
        return $this->index($request, true);
    }

    /**
     * Show the form for creating a new Customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return CustomerCreateHandler::handler($request, $this->api);
    }

    /**
     * Store a newly created Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return CustomerStoreHandler::handler($request, $this->api);
    }

    /**
     * Display the specified Customer.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
        return CustomerShowHandler::handler($request, $id, $this->api);
    }

    /**
     * Show the form for editing the specified Customer.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id = null)
    {
        return CustomerEditHandler::handler($request, $id, $this->api);
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        return CustomerUpdateHandler::handler($request, $id, $this->api);
    }

    /**
     * Remove the specified Customer from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = null)
    {
        return CustomerDeleteHandler::handler($request, $id, $this->api);
    }
}
