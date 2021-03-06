@extends('admin')

@section('title', 'User')

@section('content')


<div class="main-container-middle">
    <div class="container-header container-customer" style="overflow:visible;">
        <div class="row align-items-center justify-content-between p-3">
            <div class="col-12 col-md-6 mb-3 mb-md-0 h5 m-0">
                <a href="{{ url('admin/user/list') }}" class="btn btn-outline-primary px-2 mr-3" title="{{ Helper::trans('user.back_to_[object Object]', 'Back to user list') }}"><i class="fas fa-arrow-left"></i></a>
                <span>{{ Helper::trans('user.info_title', 'User info') }} ( {{ $model_info->first_name }} )</span>
            </div>
            
            <div class="col-12 col-md-6 d-none d-md-block text-right">
                <!-- <a href="{{ url('admin/user/edit/' . $model_info->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt mr-1"></i> {{ Helper::trans('general.edit', 'edit') }}</a> -->
                <a href="{{ url('admin/user/create') }}" class="btn btn-dark"><i class="fas fa-plus-circle mr-1"></i>{{ Helper::trans('user.user', 'Add User') }} </a>
                <a href="{{ url('admin/user/delete/'. $model_info->id) }}?redirect={{ url('admin/user/list') }}" class="btn btn-danger" data-confirmation='{{ Helper::trans('general.delete_alert', 'I you sure, you want to delete') }} "{{ $model_info->first_name }}"?'><i class="fas fa-trash mr-1"></i> {{ Helper::trans('general.delete', 'Delete') }}</a>
            </div>
        </div>
    </div>
    <div class="container-detail">
        <div class="container-customer">
            <div class="px-3 mb-3">
                {!! \App\Handlers\Admin\User\ShowFormHandler::handler(request(), $model_info->id) !!}
            </div>
        </div>
    </div>
</div>

<script>

if(typeof window.form_children_input_template == 'undefined') window.form_children_input_template = {};

function formChildInput() {
    if(! (this instanceof formChildInput)){
        return new formChildInput();
    }
    
}

formChildInput.prototype.init = function(){
    var $this = this;
    var containers_list = jQuery('[data-children]');

    for (const container of containers_list) {
        jQuery(container).on('click', '.add-item', function(){
            var c = jQuery(this).parent('[data-children]');
            var k = jQuery(c).attr('data-children');
            var i = "insert_item_" + k;

            $this.insert_item(c, 0, {}, i);
        });

        var list_key = jQuery(container).attr('data-children');
        var insert_key = "insert_item_" + list_key;
        var list = $this[list_key];

        if(Array.isArray(list)){
            for (var i = 1; i < list.length; i++) {
                var elem = list[i];
                $this.insert_item(container, i, elem, insert_key);
            }
        }
    }
}

formChildInput.prototype.insert_item = function(container, i, data, insert_key){
    // console.log(container);
    var template = '';
    var container_list = jQuery(container).find('.item-list').get(0);
    if(i == 0 && container_list){
        i = container_list.childElementCount;
    }

    if(typeof form_children_input_template[insert_key] == 'function') {
        template = form_children_input_template[insert_key](i, data)
    }

    var item_obj = null;
    item_obj = document.createElement('TR'); 
    item_obj.innerHTML = template;

    console.log(item_obj)
    if(container_list) {
        container_list.appendChild(item_obj);

        jQuery(item_obj).on('click', '.btn-delete', function(){
            if(container_list && item_obj){
                container_list.removeChild(item_obj);
            }
        });
    } else {
        console.log(container_list)
    }    
}

formChildInput().init();
</script>


@endsection
