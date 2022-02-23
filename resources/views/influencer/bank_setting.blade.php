{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           

    <div class="bg-blue transaction_box lastsection-space_b">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-md-10 col-sm-12 mx-auto section-space">
                            <div class="signup_box manage_profile common-form manage-bank">
                                <h2 class="welcome_text">Manage Bank Account Info</h2>
                                @include('user-flash-message')
                                <form id="manage_bank_details_form" action="{{url('savebankdetails')}}" method="post" enctype="multipart/form-data">
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
                                            @if(@$external_accounts['bank_name'])
                                                <div class="col-md-12">
                                                    <div class="form-group common-form_group">
                                                        <label>Bank Name</label>
                                                        <input type="text" placeholder="Account Number" name="account_number" class="form-control" value="{{@$external_accounts['bank_name']}}" readonly="">
                                                    </div>
                                                </div>
                                            @else
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
                                            @endif
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
                                            @if(!$bank)
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
                                                <div class="form-group text-center mb-0">
                                                    <button class="btn blue-btn" id="manage_bank_details_submit">Save</button>
                                                </div>
                                            </div>
                                            @else
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <button class="btn btn-danger" id="delete_bank_account">Delete Bank Account</button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection