{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 
    <section class="card-main  lastsection-space_b bg-blue section-space">
        <div class="container-lg">
            <div class="card-outer">
                <div class="card-inner">
                    <div class="card-content">
                        <h3 class="card-heading"> Add Money to Wallet </h3>
                        <div class="card-content_outer"> 
                            <form class="card-form_s h-100" method="post" id="fund-wallet-form" action="{{ url('load-wallet') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 pe-md-0">
                                        <div class="card-wrapper h-100">
                                            @if(count($cards))
                                            @foreach($cards as $key => $card)
                                            <div class="save-cards">
                                                <div class="card-detail_s">
                                                    <label class="radio-label_c">
                                                        <div class="card-inner_s d-flex align-items-start justify-content-start">
                                                            <span><img src="images/visa.png" class="card-img"></span>
                                                            <span class="holder-name"> {{$card['name']}}
                                                                <span class="card-number"> xxxx xxxx xxxx {{$card['last4']}} </span>
                                                                <p class="exp-date"> Exp. {{sprintf('%02d', $card['exp_month'])}}/{{$card['exp_year']}} </p> 
                                                            </span>
                                                            <input type="radio" class="card-radio" value="{{$card['id']}}" {{ $key == 0 ? 'checked' : '' }} name="card_id" expiry="{{ sprintf('%02d', $card['exp_month']) . '/' . $card['exp_year'] }}" cardholdername="{{ $card['name'] }}" cardnumber="xxxx xxxx xxxx {{$card['last4']}}">
                                                            <span class="checkmark"></span>
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="remove-card">
                                                    <a class="remove_card" href="javascript:;" data-id="{{$card['id']}}"> <i class="fas fa-trash-alt"></i> </a>
                                                    <!-- <p class="exp-date"> Exp. {{sprintf('%02d', $card['exp_month'])}}/{{$card['exp_year']}} </p> -->
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif

                                            <div class="save-cards new-card">
                                                <div class="card-detail_s">
                                                    <label class="radio-label_c">
                                                        <div class="card-inner_s d-flex align-items-start justify-content-start">
                                                            <span class="holder-name ms-2"> New Credit Card</span>
                                                            <input type="radio" class="card-radio new-card-radio" name="card_id" value="new" {{ !count($cards)?'checked' : "" }}>
                                                            <span class="checkmark"></span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 ps-md-0">
                                        <div class="add-card-outer">
                                            <div class="card-form_outer">
                                                <div class="card-form_wrap ">
                                                    <div class="row">
                                                        @if(session()->has('message'))
                                                        <div class="alert alert-danger">
                                                            {{ session()->get('message') }}
                                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                                        </div>
                                                        @endif
                                                        <div class="col-12">
                                                            <div class="card-form_group mb-3">
                                                                <label for="amount" class="form-label"> Amount </label>
                                                                <input type="number" name="amount" class="form-control" id="amount" placeholder="Amount">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="card-form_group mb-2">
                                                                <label for="cardName" class="form-label"> Cardholder's Name</label>
                                                                <input type="text" name="card_holder_name" class="form-control" id="cardName" placeholder="Name" value="{{@$cards[0]['name']}}" onkeydown="return alphaOnly(event);">
                                                            </div>
                                                        </div>
                                                        <div class="col-12 card-detail" style="{{ count($cards)?'display:none;' : ''}}">
                                                            <div class="card-form_group mb-3">
                                                                <label for="ccnum" class="form-label"> Card Number</label>
                                                                <div class="card-input">
                                                                    <input type="text" name="ccnum" class="form-control" id="ccnum" maxlength="19">
                                                                    <span class="card-in_img"> 
                                                                        <img src="images/visa.png"> 
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-detail col-12 col-sm-8 col-md-12 col-lg-8 pe-sm-0 pe-md-3 pe-lg-0" style="{{ count($cards)?'display:none;' : ''}}">
                                                            <div class="card-form_group mb-3">
                                                                <label for="expcard" class="form-label"> Date</label>
                                                                <div class="exp-select">
                                                                    <span class="select-arrow_span p-0">
                                                                        <select class="form-control" name="expiry_year" id="selectYear" required>
                                                                            <option value="" selected> Select Year </option>
                                                                            @for($i = 0; $i<10 ; $i++ )
                                                                            <option value="{{date('Y', strtotime('+'.$i.' years'))}}"> {{ date('Y', strtotime('+'.$i.' years')) }} </option>
                                                                            @endfor
                                                                        </select>
                                                                        <input type="hidden" id="year" name="year" value="{{@$year}}" />
                                                                    </span>
                                                                    <span class="select-arrow_span p-0">
                                                                        <select class="form-control" id="month" name="expiry_month" required> 
                                                                            <option value="" selected> Select Month </option>
                                                                            <option value="01" > January</option>
                                                                            <option value="02"> February </option>
                                                                            <option value="03"> March </option>
                                                                            <option value="04"> April </option>
                                                                            <option value="05"> May </option>
                                                                            <option value="06"> June </option>
                                                                            <option value="07"> July </option>
                                                                            <option value="08"> August </option>
                                                                            <option value="09"> September </option>
                                                                            <option value="10"> October </option>
                                                                            <option value="11"> November </option>
                                                                            <option value="12"> December </option>
                                                                        </select>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4 col-md-12 col-lg-4 card-detail" style="{{ count($cards)?'display:none;' : ''}}">
                                                            <div class="card-form_group mb-3">
                                                                <label for="amount" class="form-label"> CVV </label>
                                                                <input type="text" name="cvc" class="form-control" id="cvc" placeholder="CVV">
                                                            </div>
                                                        </div>
                                                        <div class="col-12 savedcard-detail" style="{{ !count($cards)?'display:none;' : ''}}">
                                                            <label id="savedcardNumber" class="form-label"> Card Number :- <span>xxxx xxxx xxxx {{ @$cards[0]['last4'] }}</span></label>
                                                            <label id="savedcardexpiry" class="form-label"> Expiry :- <span>{{sprintf('%02d', @$card['exp_month'])}}/{{@$card['exp_year']}}</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="common-btns">
                                                        <div class="cancel-btn">
                                                            <a href="{{ url('wallet') }}" class="flex-center"> Cancel </a>
                                                        </div>
                                                        <div class="create-btn flex-center">
                                                            <input type="submit" value="ADD" class="flex-center" id="fund-wallet-form-submit">
                                                        </div>
                                                    </div>
                                                </div>
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
    </section>
@endsection