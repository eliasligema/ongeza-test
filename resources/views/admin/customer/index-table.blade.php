
<div class="clearfix">
    <div class="px-3 pb-3">
        <!----- Include view from components/alert----->
        @component('components.alert')@endcomponent
        <!----- End include view from components/alert----->

        <div class="row align-items-center justify-content-between m-0">
            <div class="col-12 col-md-6 p-0 mb-3 mb-md-0">
                {!! pagination_header_limit($customers_list) !!}
            </div>
            <div class="col-12 col-md-6 p-0 mb-3 mb-md-0">
                {!! pagination_header_search($customers_list) !!}
            </div>
        </div>
    </div>
    
    <div class="px-3">
        @if(true)
        @foreach ($customers_list as $index => $row)
        <div class="zoom hover-container box-shadow card rounded-0 p-0 mb-2">
            <div class="py-2 px-4">
                <div class="row align-items-center">
                    <div class="col pr-0" style="max-width:50px;">{{ ($customers_list->firstItem() + $index) }}</div>
                    
                    <div class="col pr-0">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-4">
                                <a href="{{ url('admin/customer/show/'.$row->id) }}" class="media align-items-center">
                                    <div class="madia-body">
                                        <div>{{ $row->first_name }}</div>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <span class="d-block">{{ $row->last_name }}</span>
                                    </div>
                                    <div class="col">
                                        <span class="d-block">{{ $row->town_name }}</span>
                                    </div>
                                    <div class="col">
                                        <span class="d-block">{{ $row->gender_id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col text-right" style="max-width:150px;">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ url('admin/customer/show/'.$row->id) }}" class="btn btn-outline-dark btn-circle px-1 mr-2"> <i class="fas fa-file"></i> </a>
                            <a href="{{ url('admin/customer/edit/'. $row->id) }}" class="btn btn-outline-dark btn-circle px-1 mr-2 "> <i class="fas fa-pencil-alt"></i> </a>
                            <a href="{{ url('admin/customer/delete/'. $row->id) }}?redirect={{ url()->full() }}" class="btn btn-outline-danger btn-circle px-1 mr-2" data-confirmation='{{ Helper::trans('general.delete_alert', 'I you sure, you want to delete') }} "{{ $row->name }}"?'> <i class="fas fa-trash"></i> </a>
                            <a data-toggle="collapse" href="#collapse-more-{{ $row->id }}" class="btn btn-link text-dark mr-1" title="More"> <i class="fas fa-ellipsis-v fa-sm"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse bg-light" id="collapse-more-{{ $row->id }}">
                <hr class="my-0"/>
                <div class="clearfix p-3">
                    <div class="text-primary h5 mb-3">{{ Helper::trans('general.details', 'Details') }}</div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <dl class="row h-100 mb-0">
                                <dd class="col-sm-5 text-truncate">{{ Helper::trans('customer.first_name', 'First Name') }}</dd>
                                <dd class="col-sm-7 text-truncate">
                                    {{ ($row->first_name)? $row->first_name: '-' }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <dl class="row h-100 mb-0">
                                <dd class="col-sm-5 text-truncate">{{ Helper::trans('customer.last_name', 'Last Name') }}</dd>
                                <dd class="col-sm-7 text-truncate">
                                    {{ ($row->last_name)? $row->last_name: '-' }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <dl class="row h-100 mb-0">
                                <dd class="col-sm-5 text-truncate">{{ Helper::trans('customer.town_name', 'Town Name') }}</dd>
                                <dd class="col-sm-7 text-truncate">
                                    {{ ($row->town_name)? $row->town_name: '-' }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <dl class="row h-100 mb-0">
                                <dd class="col-sm-5 text-truncate">{{ Helper::trans('customer.gender_id', 'Gender') }}</dd>
                                <dd class="col-sm-7 text-truncate">
                                    {{ ($row->gender_id)? $row->gender_id: '-' }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <dl class="row h-100 mb-0">
                                <dd class="col-sm-5 text-truncate">{{ Helper::trans('customer.is_deleted', 'Is Deleted') }}</dd>
                                <dd class="col-sm-7 text-truncate">
                                    @if($row->is_deleted) 
                                        <span class="text-success">YES</span>
                                    @else
                                        <span class="text-danger">No</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="border bg-white">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-primary">
                    <tr>
                        <td>#</td>
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>Town Name</td>
                        <td>Gender</td>
                        <td>Is Deleted</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customers_list as $index => $row)
                    <tr>
                        <td scope="row">{{ ($customers_list->firstItem() + $index) }}</td>
                        <td>
                            <a class="d-block" href="{{ url('admin/customer/show/'.$row->id) }}"><?= $row->first_name;?></a>
                        </td>
                        <td>
                            <span class="d-block"><?= $row->last_name;?></span>
                        </td>
                        <td>
                            <span class="d-block"><?= $row->town_name;?></span>
                        </td>
                        <td>
                            <span class="d-block"><?= $row->gender_id;?></span>
                        </td>
                        <td>
                            @if($row->is_deleted == 1)
                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ url('admin/customer/show/'. $row->id) }}" class="btn px-1 py-0"> <i class="fas fa-file"></i> </a>
                                <a href="{{ url('admin/customer/edit/'. $row->id) }}" class="btn px-1 py-0"> <i class="fas fa-pencil-alt"></i> </a>
                                <a href="{{ url('admin/customer/delete/'. $row->id) }}?redirect={{ url()->full() }}" class="btn px-1 py-0 text-danger" data-confirmation='{{ Helper::trans('general.delete_alert', 'I you sure, you want to delete') }} "{{ $row->first_name }}"?'> <i class="fas fa-trash"></i> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                @if($customers_list->count() > 0)
                <tfoot class="table-active">
                    <tr>
                        <td>#</td>
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>Town Name</td>
                        <td>Gender</td>
                        <td>Is Deleted</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endif

        {!! pagination_footer($customers_list) !!}
    </div>
</div>