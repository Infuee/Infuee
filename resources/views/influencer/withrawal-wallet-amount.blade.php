{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 

<section class="wallet-main section-space bg-blue lastsection-space_b">
        <div class="wallet-outer">
            <div class="container">

                <div class="wallet-inner">

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="wallet-box">
                                <div class="current-balance">
                                    <h3> ${{ number_format($wallet['amount'], 2) }} </h3>
                                    <p> Your Current Wallet Balance </p>
                                </div>

                            </div>
                        </div>
         
                    </div>

                </div>


                <section class="contact-main bg-blue lastsection-space_b section-space">
                   <div class="form_layout">
                      <div class="container">
                         <div class="row">
                            <div class="col-md-10 col-lg-7 mx-auto">
                               <div class="signup_box">
                                  @if($userAccount > 0)
                                  <h2>Withrawal Wallet Amount</h2>
                                  <form action="" method="post" id="withrawalamountform" class="common-form" novalidate="novalidate">
                                    <input type="hidden" name="totalamount" id="totalamount" value="{{$wallet['amount']}}">
                                    <input type="hidden" name="minimumamt" id="minimumStripAmount" value="100">
                                     <div class="signup_form login_form">
                                        <div class="row">
                                           <div class="col-md-12">
                                              <div class="form-group common-form_group">
                                                 <input type="text" name="withralamount" value="" id="withrawalAmount" placeholder="Withrawal Wallet Amount" class="form-control">
                                                 <spen id="error"></span>
                                                
                                              </div>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="text-center send-btn_outer mt-5">
                                        <input type="submit" id="sumbitrequest" class="btn send-btn" name="" value="Send Request">
                                     </div>
                                  </form>
                                  @else
                                  <h2>Your Account not Verify!!</h2>
                                  @endif
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </section>



            </div>
        </div>
    </section>

@endsection