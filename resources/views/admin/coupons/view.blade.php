{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <!--begin::Profile Overview-->
    <div class="d-flex flex-row">
        <!--begin::Aside-->

        @if( $page !== 'add')

        <div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
            <!--begin::Profile Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end">
                        <?php  ?>
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::User-->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                           <!--  <div class="symbol-label" style="background-image:url('{{ @$user_ ? @$user_->getProfileImage() : '' }}')"></div> -->
                            <div class="symbol-label" style="background-image:url('{{ @$user_ ? @$user_->getProfileImage() : '' }}')">
                                
                            </div>
                            <i class="symbol-badge bg-success"></i>
                        </div>
                        <div>
                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$user_['first_name']. ' '. $user_['last_name']}}</a>
                            <div class="text-muted">{{@$user_['type'] == 1 ? 'User' : 'Influencer' }}</div>
                            <!-- <div class="mt-2">
                                <a href="#" class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Chat</a>
                                <a href="#" class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">Follow</a>
                            </div> -->
                        </div>
                    </div>
                    <!--end::User-->
                    <!--begin::Contact-->
                    <div class="py-9">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Email:</span>
                            <a href="#" class="text-muted text-hover-primary">{{@$user_['email']}}</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Phone:</span>
                            <span class="text-muted">{{@$user_['phone']}}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold mr-2">Followers:</span>
                            <span class="text-muted">{{@$user_['followers']}}</span>
                        </div>
                    </div>
                    <!--end::Contact-->
                    <!--begin::Nav-->
                    
                    <!--end::Nav-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Profile Card-->
        </div>

        @endif
        <!-- End of view if -->

        <!--end::Aside-->
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <form action="{{url('admin/edituser')}}" method="post" id="user_update_form" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="user_id" value="{{@$user_['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Order Details</h3>
                            @if($page !== 'view')   
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span>
                            @endif
                        </div>
                        <div class="card-toolbar">
                            @if($page !== 'view') 
                            <input type="button" id="update_user_submit" name="" class="btn btn-success mr-2 update_user_submit" value="Save Changes" />
                            <a href="{{url('admin/users')}}" class="btn btn-secondary">Cancel</a>
                            @endif
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="form-group row {{$errors->has('billing_first_name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Billing First Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="billing_first_name" value="{{@$orders->billing_first_name?:old('billing_first_name')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('billing_first_name'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="billing_first_name" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('billing_first_name') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('billing_last_name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Billing Last Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="billing_last_name" value="{{@$orders->billing_last_name?:old('billing_last_name')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('billing_last_name'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="billing_last_name" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('billing_last_name') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('billing_last_name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Description</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                       
                                        <textarea rows="5" class="form-control form-control-lg form-control-solid" name="descripion" {{$page == 'view' ? 'readonly' : ''}} >{{@$orders->description?:old('description')}}</textarea>
                                    </div>
                                    @if ($errors->has('billing_last_name'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="billing_last_name" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('billing_last_name') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('address') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Address</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="address" value="{{@$orders->address?:old('address')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('address'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="address" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('address') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('city') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">City</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="city" value="{{@$orders->city?:old('city')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('city'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="city" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('city') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('state') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">State</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="state" value="{{@$orders->state?:old('state')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('state'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="state" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('state') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('country') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Country</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="country" value="{{@$orders->country?:old('country')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('country'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="country" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('country') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('zipcode') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Zipcode</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="zipcode" value="{{@$orders->zipcode?:old('zipcode')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('zipcode'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="zipcode" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('zipcode') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($page !== 'view') 
                            <div class="form-group row">
                                <div class="col-xl-3 col-lg-3"></div>
                                <div class="col-lg-9 col-xl-6">
                                    <input type="button" name="" class="btn btn-success mr-2 update_user_submit" value="Save Changes" />
                                    <a href="{{url('admin/users')}}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                            @endif


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
