@extends('admin')

@section('title', 'User')

@section('content')
<div class="main-container-middle">
    <div class="container-header container-customer" style="overflow:visible;">
        <div class="d-flex align-items-center justify-content-between p-3">
            <div class="h5 m-0">
                <a href="{{ url('admin/user/list') }}" class="btn btn-outline-primary mr-3" title="{{ Helper::trans('user.back_to_[object Object]', 'Back to user list') }}"><i class="fas fa-arrow-left"></i></a>
                <span>{{ Helper::trans('user.create_title', 'Create user') }} </span>
            </div>
            <div>
                <!-- <a href="{{ url('admin/user/list') }}" class="btn btn-primary"><i class="fas fa-list mr-1"></i> List</a> -->
            </div>
        </div>
    </div>
    <div class="container-detail">
        <div class="container-customer">
            <div class="px-3 mb-3">
                <div class="card card-body bg-white p-5">
                    {!! \App\Handlers\Admin\User\CreateFormHandler::handler(request()) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function ajaxForm() {
    if(! (this instanceof ajaxForm)){
        return new ajaxForm();
    }
}

ajaxForm.prototype.init = function(){
    var $this = this;
    jQuery('form select').on('change', this, function(){
        // console.log(this);
        this.resetOptions = $this.options;
        var value = jQuery(this).val();
        var elem_id = jQuery(this).attr('id');
        var elem_modal_id = '#model_new' + elem_id;
        $this.submit(elem_modal_id, this);

        if(value == '<new>' && elem_id) {
            this.selectedIndex = 0;
            jQuery(elem_modal_id).modal('show');
            jQuery(elem_modal_id + ' [type=reset]').on('click', this, function(){
                $this.reset_inputs(elem_modal_id);
            });

            jQuery(elem_modal_id + ' [value="<new>"]').addClass('d-none');
        }
    })
}

ajaxForm.prototype.options = function(data, selected_id){
    var str = '';

    for (const elem of data) {
        str += '<option value="' + elem.key + '"' + ( (selected_id == elem.key)? 'selected':'' ) + '>' + elem.value + '</option>';
    }

    jQuery(this).html(str);
}

ajaxForm.prototype.submit = function(elem_modal_id, elem) {
    var $this = this;

    jQuery(elem_modal_id + " form").submit(function(e) {
        var form = jQuery(this);
        var data = form.serialize();
        var url = form.attr('action');

        $this.reset_inputs(elem_modal_id);
        jQuery.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            success: function(data){
                // console.log(data); // show response from the php script.
                
                if(data.errors){
                    $this.errors(elem_modal_id, data.errors);
                } else if(data.options_list){
                    elem.resetOptions(data.options_list, data.selected_id);
                    jQuery(elem_modal_id).modal('hide');
                    $this.reset_inputs(elem_modal_id, true);
                }
            },
            error: function(error){
                console.log(error)
                if(error.responseJSON){
                    alert(error.responseJSON.message)
                }
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
}

ajaxForm.prototype.errors = function(elem_modal_id, errors) {
    for (const key in errors) {
        if (errors.hasOwnProperty(key)) {
            const err = errors[key];
            var elem = jQuery(elem_modal_id + " #_input_" + key);
            var help_elem = jQuery(elem_modal_id + " #_input_help_" + key);
            if(elem) {
                elem.addClass('is-invalid'); 
            }

            if(help_elem && Array.isArray(err) && err.length) {
                help_elem.html(err.join(', '));
            }
        }
    }
}

ajaxForm.prototype.reset_inputs = function(modal_id, clear = false) {
    var elems_list = jQuery( modal_id + ' input,' + modal_id + ' textarea,' + modal_id + ' select' );

    for (const elem of elems_list) {
        jQuery(elem).removeClass('is-invalid');

        if(clear){
            jQuery(elem).val(null);
        }
    }
}

ajaxForm().init();


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

            $this.insert_item(c, 0, null, {}, {}, i);
        });

        var list_key = jQuery(container).attr('data-children');
        var insert_key = "insert_item_" + list_key;
        var data_list = form_children_input_value[insert_key];
        var error_list = form_children_input_error[insert_key];
        var data_errors = {}

        if(error_list) {
            for (const key in error_list) {
                if (error_list.hasOwnProperty(key)) {
                    const elem = error_list[key];
                    const key_array = key.split('.');
                    if(!data_errors[key_array[1]]) data_errors[key_array[1]] = {};

                    data_errors[key_array[1]][key_array[2]] = elem[0];
                }
            }
        }

        if(Array.isArray(data_list)){
            for (var i = 0; i < data_list.length; i++) {
                var elem_data = data_list[i];
                var elem_error = data_errors[i];
                $this.insert_item(container, i, null, elem_data, elem_error, insert_key);
            }
        } else if(data_list) {
            var count = 0;
            for (const random_id in data_list) {
                if (data_list.hasOwnProperty(random_id)) {
                    const elem_data = data_list[random_id];
                    const elem_error = (data_errors)? data_errors[random_id]: null;
                    $this.insert_item(container, count, random_id, elem_data, elem_error, insert_key);
                    count++;
                }
            }
        }
    }
}

formChildInput.prototype.insert_item = function(container, i, random_id, data, error, insert_key){
    // console.log(container);
    var template = '';
    var container_list = jQuery(container).find('.item-list').get(0);
    if(i == 0 && container_list){
        i = container_list.childElementCount;
    }

    if(typeof form_children_input_template[insert_key] == 'function') {
        template = form_children_input_template[insert_key](i, random_id, data, error)
    }

    var item_obj = null;

    if(typeof template == 'string') {
        item_obj = document.createElement('TR'); 
        item_obj.innerHTML = template;
    } else {
        item_obj = template;
    }

    // console.log(item_obj)
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