 
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
<form action="{{ url('admin/gender/store'.(isset($redirect)? '?redirect='.$redirect: null)) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <!----- Include view from components/alert----->
    @component('components.alert')@endcomponent
    <!----- End include view from components/alert----->

    <div class="clearfix">
        <!----- Start form field gender_name ----->
        <div class="form-group">
            <label class="mb-1" for="gender_name">{{ Helper::trans('gender.gender_name', 'Gender Name') }}</label>
            <input type="text" class="form-control {{ $errors->has('gender_name')? 'is-invalid': null }}" name="gender_name" value="{{ old('gender_name') }}" placeholder="{{ Helper::trans('gender.gender_name', 'Gender Name') }}" id="_input_gender_name">
            <div class="invalid-feedback" id="_input_help_gender_name">{{ $errors->has('gender_name')? $errors->first('gender_name'): null }}</div>
        </div>
        <!----- End form field gender_name ----->
        
        
        <button class="btn btn-success" type="submit"><i class="fas fa-save mr-1"></i> {{ Helper::trans('general.save', 'Save') }}</button>
        <button class="btn btn-dark" type="reset"><i class="fas fa-ban mr-1"></i> {{ Helper::trans('general.reset', 'Reset') }}</button>
    </div>
</form>

<script>
if(typeof window.form_children_input_template == 'undefined') window.form_children_input_template = {};
if(typeof window.form_children_input_value == 'undefined') window.form_children_input_value = {};
if(typeof window.form_children_input_error == 'undefined') window.form_children_input_error = {};


</script>

