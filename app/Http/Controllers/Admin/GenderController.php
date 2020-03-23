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

use App\Models\Gender;
use Illuminate\Http\Request;

use App\Handlers\Admin\Gender\IndexHandler as GenderIndexHandler;
use App\Handlers\Admin\Gender\CreateHandler as GenderCreateHandler;
use App\Handlers\Admin\Gender\StoreHandler as GenderStoreHandler;
use App\Handlers\Admin\Gender\ShowHandler as GenderShowHandler;
use App\Handlers\Admin\Gender\EditHandler as GenderEditHandler;
use App\Handlers\Admin\Gender\UpdateHandler as GenderUpdateHandler;
use App\Handlers\Admin\Gender\DeleteHandler as GenderDeleteHandler;

class GenderController extends \App\Http\Controllers\Controller
{
    /**
     * This function run first automatic before any other function
     */
    public function __construct() {
        parent::__construct();
        // Run middleware for Gender controller
        // $this->middleware(['auth']);
    }

    /**
     * Display a listing of the Gender.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return GenderIndexHandler::handler($request, $this->api);
    }

    /**
     * Display a listing of the Gender.
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
     * Show the form for creating a new Gender.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return GenderCreateHandler::handler($request, $this->api);
    }

    /**
     * Store a newly created Gender in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return GenderStoreHandler::handler($request, $this->api);
    }

    /**
     * Display the specified Gender.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
        return GenderShowHandler::handler($request, $id, $this->api);
    }

    /**
     * Show the form for editing the specified Gender.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id = null)
    {
        return GenderEditHandler::handler($request, $id, $this->api);
    }

    /**
     * Update the specified Gender in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        return GenderUpdateHandler::handler($request, $id, $this->api);
    }

    /**
     * Remove the specified Gender from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id = null)
    {
        return GenderDeleteHandler::handler($request, $id, $this->api);
    }
}
