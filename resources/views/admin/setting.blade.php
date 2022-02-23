{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')
<?php //echo '<pre>';print_r($setting); echo '</pre>'; ?>
    <!--begin::Profile Overview-->
    @if(session()->has('error'))
    <div class="alert alert-error">
    {{ session()->get('error') }}
    </div>
    @endif
    @if(session()->has('success'))
    <div class="alert alert-success">
    {{ session()->get('success') }}
    </div>
    @endif

<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Settings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Bank Details</a>
    </li>
    
</ul><!-- Tab panes -->

        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="d-flex flex-row">
        <!--begin::Aside-->

        <div class="flex-row-fluid">
            <form action="{{url('admin/web-settings')}}" id="website-setting" method="post" enctype="multipart/form-data" autocomplete="off">
               @csrf
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column justify-content-center">
                            <h3 class="card-label font-weight-bolder text-dark">Website Settings</h3>
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="website-setting-submit" name="" class="btn btn-blue mr-2" value="Save" />
                            <a href="{{url('admin/categories')}}" class="btn btn-dark">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Setting Details</h5>
                                </div>
                            </div>
                            
                            <div class="form-group row {{$errors->has('name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Twilio Account SID</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="twilio_accont_sid" value="{{old('twilio_accont_sid')?:@$setting['twilio_accont_sid']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('twilio_accont_sid'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="twilio_accont_sid" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('twilio_accont_sid') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="form-group row {{$errors->has('twilio_auth_token') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Twilio Auth Token</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="twilio_auth_token" value="{{old('twilio_auth_token')?:@$setting['twilio_auth_token']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('twilio_auth_token'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="twilio_auth_token" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('twilio_auth_token') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('twilio_from') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Twilio From Number</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="twilio_from" value="{{old('twilio_from')?:@$setting['twilio_from']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('twilio_from'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="twilio_from" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('twilio_from') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('google_api_key') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Google API Key</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="google_api_key" value="{{old('google_api_key')?:@$setting['google_api_key']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('google_api_key'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="google_api_key" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('google_api_key') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('stripe_pk') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Stripe Public Key</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="stripe_pk" value="{{old('stripe_pk')?:@$setting['stripe_pk']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('stripe_pk'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="stripe_pk" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('stripe_pk') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('stripe_sk') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Stripe Secret Key</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="stripe_sk" value="{{old('stripe_sk')?:@$setting['stripe_sk']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('stripe_sk'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="stripe_sk" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('stripe_sk') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('stripe_currency') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Stripe Currency</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="stripe_currency" value="{{old('stripe_currency')?:@$setting['stripe_currency']}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('stripe_currency'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="stripe_currency" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('stripe_currency') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('smtp_username') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">SMTP Username</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="smtp_username" value="{{old('smtp_username')?:@$setting['smtp_username']}}" autocomplete="password_new" />
                                </div>
                                @if ($errors->has('smtp_username'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="stripe_sk" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('smtp_username') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('smtp_password') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">SMTP Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="smtp_password" value="{{old('smtp_password')?:@$setting['smtp_password']}}" autocomplete="password_new" />
                                </div>
                                @if ($errors->has('smtp_password'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="smtp_password" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('smtp_password') }}
                                    </div>
                                </div>
                                @endif
                            </div> 

                            <div class="form-group row {{$errors->has('commission') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Commission ( % )</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="commission" value="{{old('commission')?:@$setting['commission']}}" autocomplete="password_new" />
                                </div>
                                @if ($errors->has('commission'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="commission" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('commission') }}
                                    </div>
                                </div>
                                @endif
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
            </div>



            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="bg-white transaction_box lastsection-space_b">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-md-10 col-sm-12 mx-auto py-5 section-space">
                            <div class="signup_box manage_profile common-form">
                                <h2 class="welcome_text">Manage Bank Account Info</h2>
                                @if(session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                <form id="manage_bank_details_form" action="{{url('admin/save/bankdetails')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="bank_account" value="{{@$bankSetting['account_id']}}">
                                    <div class="signup_form login_form">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{$errors->has('account_holder') ? 'has-danger' : ''}} common-form_group">
                                                    <label>Account Holder Name</label>
                                                    <div class="Pos-R">
                                                        <input type="text" placeholder="Name" name="account_holder" class="form-control" value="{{old('account_holder')?:(@$external_accounts['account_holder_name']?:$user['first_name'] . ' ' . $user['last_name'])}}" {{@$external_accounts['bank_name']?'readonly':""}}>
                                                        @if ($errors->has('account_holder'))
                                                        <div class="fv-plugins-message-container">
                                                            <div data-field="account_holder" data-validator="notEmpty" class="fv-help-block">
                                                                {{ $errors->first('account_holder') }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                                <div class="col-md-12">
                                                    <div class="form-group common-form_group">
                                                        <label>Bank Name</label>
                                                        <input type="text" placeholder="Account name" name="account_number" class="form-control" value="{{@$external_accounts['bank_name']}}" >
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-12">
                                                    <div class="form-group common-form_group">
                                                        <label>ID Number ( Bank Id Proof )</label>
                                                        <input type="text" placeholder="ID Number" name="personal_id_number" class="form-control" value="{{old('personal_id_number')?:@$external_accounts['bank_name']}}">
                                                        @if ($errors->has('personal_id_number'))
                                                        <div class="fv-plugins-message-container">
                                                            <div data-field="personal_id_number" data-validator="notEmpty" class="fv-help-block">
                                                                {{ $errors->first('personal_id_number') }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                          
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group common-form_group">
                                                    <label>Bank Account Number</label>
                                                    <input type="text" placeholder="Account Number" name="account_number" class="form-control" value="{{old('account_number')?:(@$external_accounts['last4']?'**..**'.@$external_accounts['last4']:'')}}" {{@$external_accounts['bank_name']?'readonly':""}}>
                                                    @if ($errors->has('account_number'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="account_number" data-validator="notEmpty" class="fv-help-block">
                                                            {{ $errors->first('account_number') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group multi-form-group common-form_group">
                                                    <label>Sort Code (IFSC Code)</label>
                                                    <input id="sortCode" type="text" placeholder="Sort Code (IFSC Code)" name="sortCode" class="form-control" value="{{old('sortCode')?:@$external_accounts['routing_number']}}" {{@$external_accounts['bank_name']?'readonly':""}}>
                                                    @if ($errors->has('sortCode'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="sortCode" data-validator="notEmpty" class="fv-help-block">
                                                            {{ $errors->first('sortCode') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group common-form_group">
                                                    <label>Photo ID (Front)</label>
                                                    <input id="file" required="" class="validate form-control full-width" name="documentFront" type="file" accept="image/*">
                                                    @if ($errors->has('documentFront'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="documentFront" data-validator="notEmpty" class="fv-help-block">
                                                            {{ $errors->first('documentFront') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group common-form_group">
                                                    <label>Photo ID (Back)</label>
                                                    <input id="Backfile" required="" class="validate form-control full-width" name="documentBack" type="file" accept="image/*">
                                                    @if ($errors->has('documentBack'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="documentBack" data-validator="notEmpty" class="fv-help-block">
                                                            {{ $errors->first('documentBack') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group common-form_group">
                                                    <label>Additional Document (Front)</label>
                                                    <input id="adffile" class="validate form-control full-width" name="additionalDocumentFront" type="file" accept="image/*">
                                                    @if ($errors->has('additionalDocumentFront'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="additionalDocumentFront" data-validator="notEmpty" class="fv-help-block">
                                                            {{ $errors->first('additionalDocumentFront') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group common-form_group">
                                                    <label>Additional Document (Back)</label>
                                                    <input id="adbfile" class="validate form-control full-width" name="additionalDocumentBack" type="file" accept="image/*">
                                                    @if ($errors->has('additionalDocumentBack'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="additionalDocumentBack" data-validator="notEmpty" class="fv-help-block">
                                                            {{ $errors->first('additionalDocumentBack') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <button class="btn btn-blue mr-2" id="manage_bank_details_submit">Save</button>
                                                </div>
                                            </div>
                                          
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
           
        </div>



@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
@endsection


