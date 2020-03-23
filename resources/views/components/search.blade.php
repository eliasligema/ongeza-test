<form action="{{ url('services') }}" class="row page-search-form pb-5 px-md-5">
    <div class="col-12 col-lg-6 pr-lg-0">
        <div class="form-group m-lg-0">
            <label for="search" class="sr-only">{{ Helper::trans('general.search', 'Search') }}</label>
            <input type="text" name="main_search" value="{{ request('main_search') }}" class="form-control form-control-lg search-input" placeholder="{{ Helper::trans('general.search_placeholder', 'What are you looking for...') }}">
        </div>
    </div>
    <div class="col-12 col-lg-4 px-lg-2">
        <div class="form-group m-lg-0">
            <label for="category" class="sr-only">{{ Helper::trans('general.category', 'Category') }}</label>
            <select name="category" class="form-control form-control-lg category-input">
                <option value="">{{ Helper::trans('general.all_category', 'All category') }}</option>
                @foreach(Helper::categories() as $row)
                <option value="{{ $row->id }}" {{ (request('category') == $row->id)? 'selected':null }}>{{ $row->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12 col-lg-2 pl-lg-0 mb-3 m-lg-0 ">
        <button type="submit" class="btn btn-primary btn-block btn-lg px-3">{{ Helper::trans('general.search', 'Search') }}</button>
    </div>
</form>