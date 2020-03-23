<?php 

function pagination_header($data){
    $str = '';

    $str .= '
    <div class="d-flex align-items-center justify-content-between p-3">
        <div>';

    $str .= pagination_header_limit($data);
            
    $str .= '
        </div>';

    $str .= pagination_header_search($data);
        
    $str .= '
    </div>';

    echo $str;
}


function pagination_header_limit($data){
    $str = '';

    $str .= '
    <form page_limit_form action="'.$data->url($data->currentPage()).'" class="form-group col-12 col-md-4 float-left m-0 p-0">
        ';
        
        $per_page = ' per page';
        foreach (request()->all() as $key => $value) {
            $str .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
        }
        
        $str .= '
        <select name="limit" class="form-control" onchange="pageLimitChange()">
            <option value="5" '.( ( ($data->perPage() == '5') )? 'selected':'' ).'>5 '.$per_page.'</option>
            <option value="10" '.( ( ($data->perPage() == '10') )? 'selected':'' ).'>10 '.$per_page.'</option>
            <option value="25" '.( ( ($data->perPage() == '25') )? 'selected':'' ).'>25 '.$per_page.'</option>
            <option value="50" '.( ( ($data->perPage() == '50') )? 'selected':'' ).'>50 '.$per_page.'</option>
            <option value="100" '.( ( ($data->perPage() == '100') )? 'selected':'' ).'>100 '.$per_page.'</option>
        </select>
    </form>
    <script>
    function pageLimitChange() {
        var form = document.querySelector(\'[page_limit_form]\');
        if(form) {
            form.submit();
        }
    }
    </script>';

    return $str;
}

function pagination_header_limit_sm($data){
    $str = '';

    $str .= '
    <form page_limit_form action="'.$data->url($data->currentPage()).'" class="form-group col-12 col-md-4 float-left m-0 p-0">
        ';
        
        $per_page = ' per page';
        foreach (request()->all() as $key => $value) {
            $str .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
        }
        
        $str .= '
        <select name="limit" class="form-control" onchange="pageLimitChange()">
            <option value="5" '.( ( ($data->perPage() == '5') )? 'selected':'' ).'>5 '.$per_page.'</option>
            <option value="10" '.( ( ($data->perPage() == '10') )? 'selected':'' ).'>10 '.$per_page.'</option>
            <option value="25" '.( ( ($data->perPage() == '25') )? 'selected':'' ).'>25 '.$per_page.'</option>
            <option value="50" '.( ( ($data->perPage() == '50') )? 'selected':'' ).'>50 '.$per_page.'</option>
            <option value="100" '.( ( ($data->perPage() == '100') )? 'selected':'' ).'>100 '.$per_page.'</option>
        </select>
    </form>
    <script>
    function pageLimitChange() {
        var form = document.querySelector(\'[page_limit_form]\');
        if(form) {
            form.submit();
        }
    }
    </script>';

    return $str;
}


function pagination_header_search($data){
    $str = '';

    $str .= '
    <form class="col-12 col-md-6 float-right p-0">
        <input type="hidden" name="page" value="'.$data->currentPage().'" />
        <input type="hidden" name="limit" value="'.$data->perPage().'" />
        <div class="input-group input-group-search bg-white ">
            <input class="form-control" type="search" value="'.request('__search').'" name="__search" placeholder="Search..."/>
            <span class="input-group-append">
                <button type="submit" class="btn">
                    <i class="fa fa-search text-light"></i>
                </button >
            </span>
        </div>
    </form>';

    return $str;
}

function pagination_footer($data){
    
    $str = '';

    
    if($data->total() == 0){
        $str .= '
        <div class="clearfix p-3 border-bottom">
            <div class="pb-3 px-3">No data found</div>
        </div>';
    }
    
    $str .='
    <div class="clearfix p-3">';

    $str .= pagination_footer_pages($data);
        
    $str .= '
    </div>
    ';

    echo $str;
}

function pagination_footer_pages($data) {
    $str = '
    <style>
    .pagination {
        -webkit-box-pack: end!important;
        -ms-flex-pack: end!important;
        justify-content: flex-end!important
    }
    </style>';
    

    $str .= '
    <div class="row">
        <div class="col px-0">
            <ul class="nav justify-content-start">';
                
                $str .= '
                <li class="page-item"><span class="page-link bg-transparent text-muted border-0">Page ' . $data->currentPage() . ' / ' . $data->lastPage() . '</span></li>';

            $str .= '
            </ul>
        </div>
        <div class="col px-0">';
        
            $str .= $data->appends(request()->all())->onEachSide(1)->links();
        
            $str .= '
        </div>
    </div>';

    return $str;
}


function user($key = null) 
{
    // $user = Auth::user();
    if(session('admin_active_user_id')) {
        $user = User::where('user_id', session('admin_active_user_id'))->first();
    } else {
        $user = Auth::user();
    }

    if($user && isset($user->$key)){
        return $user->$key;
    } elseif($key == null) {
        return $user;
    }

    return null;
}

function setting($key = null){
    $setting = DB::table('settings')->first();

    if(!$setting) {
        DB::table('setting')->insert([]);
        $setting = DB::table('settings')->first();
    }

    if($setting && isset($setting->$key)){
        return $setting->$key;
    } elseif($key == null) {
        return $setting;
    }

    return null;
}
