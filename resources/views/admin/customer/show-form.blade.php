 
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
        
            <!----- Start form field first_name ----->
            <div class="form-group form-row form-view mx-0">
                <label class="col-3 col-form-label">{{ Helper::trans('customer.first_name', 'First Name') }}</label>
                <div class="py-2 col">{{ ($model_info->first_name)? $model_info->first_name: '-' }}</div>
            </div>
            <!----- End form field first_name ----->
        
            <!----- Start form field last_name ----->
            <div class="form-group form-row form-view mx-0">
                <label class="col-3 col-form-label">{{ Helper::trans('customer.last_name', 'Last Name') }}</label>
                <div class="py-2 col">{{ ($model_info->last_name)? $model_info->last_name: '-' }}</div>
            </div>
            <!----- End form field last_name ----->
        
            <!----- Start form field town_name ----->
            <div class="form-group form-row form-view mx-0">
                <label class="col-3 col-form-label">{{ Helper::trans('customer.town_name', 'Town Name') }}</label>
                <div class="py-2 col">{{ ($model_info->town_name)? $model_info->town_name: '-' }}</div>
            </div>
            <!----- End form field town_name ----->
        
            <!----- Start form field gender_id ----->
            <div class="form-group form-row form-view mx-0">
                <label class="col-3 col-form-label">{{ Helper::trans('customer.gender_id', 'Gender') }}</label>
                <div class="py-2 col">{{ ($model_info->gender_id)? $model_info->gender_id: '-' }}</div>
            </div>
            <!----- End form field gender_id ----->
        
            <!----- Start form field is_deleted ----->
            <div class="form-group form-row form-view mx-0">
                <label class="col-3 col-form-label">{{ Helper::trans('customer.is_deleted', 'Is Deleted') }}</label>
                <div class="form-control col">
                    @if($model_info->is_deleted) 
                        <span class="text-success">YES</span>
                    @else
                        <span class="text-danger">No</span>
                    @endif
                </div>
            </div>
            <!----- End form field is_deleted ----->
        
            <!----- Link to the edit page ----->
            <a class="btn btn-outline-dark mt-4" href="{{ url('admin/customer/show/'.$model_info->id.'/edit') }}"><i class="fas fa-pencil-alt mr-1"></i> {{ Helper::trans('general.edit', 'Edit') }}</a>
    </div>
</div>