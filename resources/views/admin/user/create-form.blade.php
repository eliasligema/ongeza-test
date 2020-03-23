 
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
<form action="{{ url('admin/user/store'.(isset($redirect)? '?redirect='.$redirect: null)) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <!----- Include view from components/alert----->
    @component('components.alert')@endcomponent
    <!----- End include view from components/alert----->

    <div class="clearfix">
        <!----- Start form field first_name ----->
        <div class="form-group">
            <label class="mb-1" for="first_name">{{ Helper::trans('user.first_name', 'First Name') }}</label>
            <input type="text" class="form-control {{ $errors->has('first_name')? 'is-invalid': null }}" name="first_name" value="{{ old('first_name') }}" placeholder="{{ Helper::trans('user.first_name', 'First Name') }}" id="_input_first_name">
            <div class="invalid-feedback" id="_input_help_first_name">{{ $errors->has('first_name')? $errors->first('first_name'): null }}</div>
        </div>
        <!----- End form field first_name ----->
        
        <!----- Start form field second_name ----->
        <div class="form-group">
            <label class="mb-1" for="second_name">{{ Helper::trans('user.second_name', 'Second Name') }}</label>
            <input type="text" class="form-control {{ $errors->has('second_name')? 'is-invalid': null }}" name="second_name" value="{{ old('second_name') }}" placeholder="{{ Helper::trans('user.second_name', 'Second Name') }}" id="_input_second_name">
            <div class="invalid-feedback" id="_input_help_second_name">{{ $errors->has('second_name')? $errors->first('second_name'): null }}</div>
        </div>
        <!----- End form field second_name ----->
        
        <!----- Start form field last_name ----->
        <div class="form-group">
            <label class="mb-1" for="last_name">{{ Helper::trans('user.last_name', 'Last Name') }}</label>
            <input type="text" class="form-control {{ $errors->has('last_name')? 'is-invalid': null }}" name="last_name" value="{{ old('last_name') }}" placeholder="{{ Helper::trans('user.last_name', 'Last Name') }}" id="_input_last_name">
            <div class="invalid-feedback" id="_input_help_last_name">{{ $errors->has('last_name')? $errors->first('last_name'): null }}</div>
        </div>
        <!----- End form field last_name ----->
        
        <!----- Start form field username ----->
        <div class="form-group">
            <label class="mb-1" for="username">{{ Helper::trans('user.username', 'Username') }}</label>
            <input type="text" class="form-control {{ $errors->has('username')? 'is-invalid': null }}" name="username" value="{{ old('username') }}" placeholder="{{ Helper::trans('user.username', 'Username') }}" id="_input_username">
            <div class="invalid-feedback" id="_input_help_username">{{ $errors->has('username')? $errors->first('username'): null }}</div>
        </div>
        <!----- End form field username ----->
        
        <!----- Start form field email ----->
        <div class="form-group">
            <label class="mb-1" for="email">{{ Helper::trans('user.email', 'Email') }}</label>
            <input type="text" class="form-control {{ $errors->has('email')? 'is-invalid': null }}" name="email" value="{{ old('email') }}" placeholder="{{ Helper::trans('user.email', 'Email') }}" id="_input_email">
            <div class="invalid-feedback" id="_input_help_email">{{ $errors->has('email')? $errors->first('email'): null }}</div>
        </div>
        <!----- End form field email ----->
        
        <!----- Start form field phone ----->
        <div class="form-group">
            <label class="mb-1" for="phone">{{ Helper::trans('user.phone', 'Phone') }}</label>
            <input type="text" class="form-control {{ $errors->has('phone')? 'is-invalid': null }}" name="phone" value="{{ old('phone') }}" placeholder="{{ Helper::trans('user.phone', 'Phone') }}" id="_input_phone">
            <div class="invalid-feedback" id="_input_help_phone">{{ $errors->has('phone')? $errors->first('phone'): null }}</div>
        </div>
        <!----- End form field phone ----->
        
        <!----- Start form field profile_url ----->
        <div class="form-group">
            <label class="mb-1" for="profile_url">{{ Helper::trans('user.profile_url', 'Profile Url') }}</label>
            <div class="custom-file">
                <input name="profile_url" type="file" class="custom-file-input"  id="_input_profile_url">
                <label class="custom-file-label" for="_input_profile_url">{{ Helper::trans('user.select_profile_url_file', 'Choose profile url file') }}</label>
            </div>
            <div class="invalid-feedback" id="_input_help_profile_url">{{ $errors->has('profile_url')? $errors->first('profile_url'): null }}</div>
        </div>
        <!----- End form field profile_url ----->
        
        <!----- Start form field password ----->
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label class="mb-1" for="password">{{ Helper::trans('user.password', 'Password') }}</label>
                    <input type="password" class="form-control {{ $errors->has('password')? 'is-invalid': null }}" name="password" value="{{ old('password') }}" placeholder="{{ Helper::trans('user.password', 'Password') }}" id="_input_password">
                    <div class="invalid-feedback" id="_input_help_password">{{ $errors->has('password')? $errors->first('password'): null }}</div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="mb-1" for="password_confirmation">{{ Helper::trans('user.password_confirmation', 'Password confirmation') }}</label>
                    <input type="password" class="form-control {{ $errors->has('password_confirmation')? 'is-invalid': null }}" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ Helper::trans('user.password_confirmation', 'Password confirmation') }}" id="_input_password">
                    <div class="invalid-feedback" id="_input_help_password_confirmation">{{ $errors->has('password_confirmation')? $errors->first('password_confirmation'): null }}</div>
                </div>
            </div>
        </div>
        <!----- End form field password ----->
        
        <!----- Start form field role ----->
        <div class="form-group">
            <label class="mb-1" for="role">{{ Helper::trans('user.role', 'Role') }}</label>
            <div class="clearfix">
                <label class="custom-control-inline custom-radio">
                    <input type="radio" class="custom-control-input {{ $errors->has('role')? 'is-invalid': null }}" id="role_1" name="role" value="1" {{ (old('role') == 1)? 'checked':null }}>
                    <span class="custom-control-label" for="role_1">Super admin</span>
                </label>
                <label class="custom-control-inline custom-radio">
                    <input type="radio" class="custom-control-input {{ $errors->has('role')? 'is-invalid': null }}" id="role_2" name="role" value="2" {{ (old('role') == 2)? 'checked':null }}>
                    <span class="custom-control-label" for="role_2">Admin</span>
                </label>
                <label class="custom-control-inline custom-radio">
                    <input type="radio" class="custom-control-input {{ $errors->has('role')? 'is-invalid': null }}" id="role_3" name="role" value="3" {{ (old('role') == 3)? 'checked':null }}>
                    <span class="custom-control-label" for="role_3">Member</span>
                </label>
            </div>
            <div class="invalid-feedback" id="_input_help_role">{{ $errors->has('role')? $errors->first('role'): null }}</div>
        </div>
        <!----- End form field role ----->
        
        <!----- Start form field status ----->
        <div class="form-group">
            <label class="mb-1" for="status">{{ Helper::trans('user.status', 'Status') }}</label>
            <div class="clearfix">
                <label class="custom-control-inline custom-radio">
                    <input type="radio" class="custom-control-input {{ $errors->has('status')? 'is-invalid': null }}" id="status_1" name="status" value="1" {{ (old('status') == 1)? 'checked':null }}>
                    <span class="custom-control-label" for="status_1">Active</span>
                </label>
                <label class="custom-control-inline custom-radio">
                    <input type="radio" class="custom-control-input {{ $errors->has('status')? 'is-invalid': null }}" id="status_0" name="status" value="0" {{ (old('status') == 0)? 'checked':null }}>
                    <span class="custom-control-label" for="status_0">Inactive</span>
                </label>
                <label class="custom-control-inline custom-radio">
                    <input type="radio" class="custom-control-input {{ $errors->has('status')? 'is-invalid': null }}" id="status_2" name="status" value="2" {{ (old('status') == 2)? 'checked':null }}>
                    <span class="custom-control-label" for="status_2">Banned</span>
                </label>
            </div>
            <div class="invalid-feedback" id="_input_help_status">{{ $errors->has('status')? $errors->first('status'): null }}</div>
        </div>
        <!----- End form field status ----->
        
        
        <button class="btn btn-success" type="submit"><i class="fas fa-save mr-1"></i> {{ Helper::trans('general.save', 'Save') }}</button>
        <button class="btn btn-dark" type="reset"><i class="fas fa-ban mr-1"></i> {{ Helper::trans('general.reset', 'Reset') }}</button>
    </div>
</form>

<script>
if(typeof window.form_children_input_template == 'undefined') window.form_children_input_template = {};
if(typeof window.form_children_input_value == 'undefined') window.form_children_input_value = {};
if(typeof window.form_children_input_error == 'undefined') window.form_children_input_error = {};


</script>

