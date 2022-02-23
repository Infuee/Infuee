{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <!--begin::Profile Overview-->
    <div class="d-flex flex-row">
        <!--end::Aside-->
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <form action="{{url('admin/home-page-middle-section')}}" method="post" id="user_update_form" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="user_id" value="{{@$user_['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Home Page Middle Section</h3>
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="update_user_submit" name="" class="btn btn-success mr-2" value="Save Changes">
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <textarea style="display: none;" id="editor" name="editor">{{@$content['home_page_middle_section']}}</textarea>
                                </div>
                            </div>
                            

                        </div>
                        <!--end::Body-->
                </div>
                <!--end::Advance Table Widget 7-->
            </form>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Overview-->

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
    <script>
        $('.loader').show();
        setTimeout(function(){  
            $('.loader').hide();
        }, 500);
    </script>
@endsection
