{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <!--begin::Profile Overview-->
    <div class="d-flex flex-row">
        <!--begin::Aside-->

       
        <!-- End of view if -->

        <!--end::Aside-->
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <form action="{{url('admin/add-edit-faq/'.$id)}}" method="post" id="faq_form" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="user_id" value="{{@$user_['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Add / Edit FAQ</h3>
                                <!-- <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span> -->
                        </div>
                        <div class="card-toolbar">
                            <input type="button" id="update_faq_submit" name="" class="btn btn-success mr-2" value="Save Changes" />
                            <a href="{{url('admin/faq-cat-list')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">FAQ Details</h5>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Category</label>
                                <div class="col-lg-9 col-xl-6">
                                    <select class="form-control form-control-lg form-control-solid" name="cat_id">
                                        <option value="">Select Category</option>
                                        @foreach(@$faq_cat AS $cat)
                                            @php
                                                $select = '';
                                                if(!empty($FaqData)){
                                                    if($cat->id == $FaqData->cat_id){
                                                        $select = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{$cat->id}}" {{$select}}>{{$cat->cat_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Question</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="question" value="{{@$FaqData->question}}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Answer</label>
                                <div class="col-lg-9 col-xl-6">
                                    <textarea class="form-control form-control-lg form-control-solid" name="answer" rows="10">{{@$FaqData->answer}}</textarea>
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
@endsection


