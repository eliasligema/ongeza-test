  
<style>
.item-list td,
.item-list th {
    padding-top:3px;
    padding-bottom:3px;
    padding-left:0px;
}

.item-list .form-control {
    background: none;
    border-color: transparent;
    border-bottom-color: gainsboro;
}
</style>
<form action="{{ url('admin/customer/update/'.$model_info->id.(isset($redirect)? '?redirect='.$redirect: null)) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <!----- Include view from components/alert ----->
    @component('components.alert')@endcomponent
    <!----- End include view from components/alert ----->

    <div class="clearfix">
        <!----- Start form field first_name ----->
        <div class="form-group">
            <label class="mb-1" for="first_name">First Name</label>
            <input type="text" class="form-control {{ $errors->has('first_name')? 'is-invalid': null }}" name="first_name" value="{{ $model_info->first_name }}" placeholder="First Name" id="_input_first_name">
            <div class="invalid-feedback" id="_help_input_first_name">{{ $errors->has('first_name')? $errors->first('first_name'): null }}</div>
        </div>
        <!----- End form field first_name ----->
        <!----- Start form field last_name ----->
        <div class="form-group">
            <label class="mb-1" for="last_name">Last Name</label>
            <input type="text" class="form-control {{ $errors->has('last_name')? 'is-invalid': null }}" name="last_name" value="{{ $model_info->last_name }}" placeholder="Last Name" id="_input_last_name">
            <div class="invalid-feedback" id="_help_input_last_name">{{ $errors->has('last_name')? $errors->first('last_name'): null }}</div>
        </div>
        <!----- End form field last_name ----->
        <!----- Start form field town_name ----->
        <div class="form-group">
            <label class="mb-1" for="town_name">Town Name</label>
            <input type="text" class="form-control {{ $errors->has('town_name')? 'is-invalid': null }}" name="town_name" value="{{ $model_info->town_name }}" placeholder="Town Name" id="_input_town_name">
            <div class="invalid-feedback" id="_help_input_town_name">{{ $errors->has('town_name')? $errors->first('town_name'): null }}</div>
        </div>
        <!----- End form field town_name ----->
        <!----- Start form field gender_id ----->
        <div class="form-group">
            <label class="mb-1" for="gender_id">{{ Helper::trans('customer.gender_id', 'Gender') }}</label>
            <select class="form-control {{ $errors->has('gender_id')? 'is-invalid': null }}" name="gender_id" id="_input_gender_id">
                @foreach($gender as $row)
                <option value="{{ $row->id }}" {{ ($model_info->gender_id == $row->id)? 'selected':null }}>{{ $row->gender_name }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback" id="_input_help_gender_id">{{ $errors->has('gender_id')? $errors->first('gender_id'): null }}</div>
        </div>
        <!----- End form field gender_id ----->
        <!----- Start form field is_deleted ----->
        <!-- <div class="form-group">
            <label class="mb-1" for="is_deleted">Is Deleted</label>
            <div class="custom-control custom-switch {{ $errors->has('is_deleted')? 'is-invalid': null }}">
                <input type="checkbox" class="custom-control-input" id="is_deleted" name="is_deleted" id="_input_is_deleted" {{ ($model_info->is_deleted)? 'checked':null }}>
                <label class="custom-control-label" for="is_deleted">Please check if is deleted</label>
            </div>
            <div class="invalid-feedback" id="_help_input_is_deleted">{{ $errors->has('is_deleted')? $errors->first('is_deleted'): null }}</div>
        </div> -->
        <!----- End form field is_deleted ----->
        
        <button class="btn btn-success" type="submit"><i class="fas fa-save mr-1"></i> {{ Helper::trans('general.update', 'Update') }}</button>
    </div>
    
</form>


<script>
if(typeof window.form_children_input_template == 'undefined') window.form_children_input_template = {};
if(typeof window.form_children_input_value == 'undefined') window.form_children_input_value = {};
if(typeof window.form_children_input_error == 'undefined') window.form_children_input_error = {};
</script>