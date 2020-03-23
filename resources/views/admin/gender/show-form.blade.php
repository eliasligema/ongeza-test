 
<style>
.form-view {
    margin-bottom: 0;
    border-bottom: 1px dashed #ced4da;
}

.form-view .form-control {
    border: none;
    background: transparent;
}
</style>

<div class="clearfix p-5">

    <!----- Include view from components/alert----->
    @component('components.alert')@endcomponent
    <!----- End include view from components/alert----->
    
    <div class="card card-body col-12 col-md-10 m-auto">
        
            <!----- Start form field gender_name ----->
            <div class="form-group form-row form-view mx-0">
                <label class="col-3 col-form-label">{{ Helper::trans('gender.gender_name', 'Gender Name') }}</label>
                <div class="py-2 col">{{ ($model_info->gender_name)? $model_info->gender_name: '-' }}</div>
            </div>
            <!----- End form field gender_name ----->
        
            <!----- Link to the edit page ----->
            <a class="btn btn-outline-dark mt-4" href="{{ url('admin/gender/show/'.$model_info->id.'/edit') }}"><i class="fas fa-pencil-alt mr-1"></i> {{ Helper::trans('general.edit', 'Edit') }}</a>
    </div>
</div>