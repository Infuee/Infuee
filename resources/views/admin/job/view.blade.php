{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{@$category['name']}} {!! @$category->statusBadge() !!} </h3>
            </div>
            <div class="card-toolbar">

                
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <!--begin::Search Form-->
            <div class="mb-7">
                @include('flash-message')
                <form action="{{url('admin/plan-category')}}/{{@$category['id']}}">
                
                <div class="row align-items-center">
                    <div class="col-lg-9 col-xl-8">
                        <div class="row align-items-center">
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="1" {{@$inputs['status'] == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="2" {{@$inputs['status'] == 2 ? 'selected' : ''}}>Deleted</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                <input type="submit" name="" class="btn btn-light-primary px-6 font-weight-bold" value="Search">
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <!--begin: Datatable-->
            <table class="datatable datatable-bordered datatable-head-custom" id="category_package_data_listing">
                <thead>
                <tr>
                    <th title="ID">ID</th>
                    <th title="Name">Name</th>
                    <th title="Name">Description</th>
                    <th title="Name">Commission</th>
                    <th title="Name">Status</th>
                    <th title="Ation">Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable-->
        </div>
    </div>

@endsection

{{-- Styles Section --}}
@section('styles')

@endsection
