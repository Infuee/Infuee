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
                                    <h3> ${{ number_format(@$wallet['amount'], 2) }} </h3>
                                    <p> Current Wallet Balance </p>
                                </div>
                                @if(Auth::user()->type == '6')
                                <div class="current-balance">
                                    <a href="{{ url('withrawal-wallet-amount') }}" class=""> Withdraw Wallet Amount </a>
                                </div>
                                @endif

                                
                                <div class="dark-btn wallet-btn ms-auto">
                                    <a href="{{ url('fund-wallet') }}" class=""><i class="fas fa-plus-circle"></i> Add Money To
                                        Wallet </a>
                                </div>
                               

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="transaction-detail_outer">
                                <h4> All Transaction Detail </h4>
                                <div class="transaction-table">
                                    <div class="table-responsive">
                                        <table class="table table-fixed table-borderless align-middle">
                                            <thead>
                                                <tr>
                                                    <th> Campaigning Name </th>
                                                    <th class="text-center"> Withdrawal </th>
                                                    <th class="text-center"> Deposit </th>
                                                    <th class="text-center"> Status </th>
                                                    <th> Invoice </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($transactions->total())
                                                    @foreach($transactions as $transaction)
                                                        <tr>
                                                            <td>
                                                                <div class="transaction-content">
                                                                    <div class="trans-img">
                                                                        @if(is_file(public_path("/media/users").'/'.@$transaction['user']['image']))
                                                                            <img src="{{ Helpers::asset('media/users/'.$transaction['user']['image'])}}">
                                                                        @else
                                                                            <img src="{{ Helpers::asset('media/users/blank.png') }}">
                                                                        @endif
                                                                    </div>
                                                                    <div class="trans-text_content">
                                                                        <h5> {{ ucwords(@$transaction['user']['first_name'] . ' ' . @$transaction['user']['last_name'] ) }} </h5>
                                                                        <p> Transaction ID : <span> {{ $transaction['transaction_id'] != '' ? $transaction['transaction_id'] : sprintf('%08d', $transaction['id']) }} </span> </p>
                                                                        <p> <span class="date-span"> {{ date('d F, Y', strtotime($transaction['created_at'])) }}</span> <span class="time-span"> {{ date('h:i A', strtotime($transaction['created_at'])) }} </span> </p>
                                                                    </div>
                                                                </div>
                                                                <td class="text-center"> 
                                                                    @if($transaction['transaction_type'] == \App\Models\UserWalletTransaction::TYPE_DABIT )
                                                                        ${{ number_format($transaction['amount'], 2) }} 
                                                                    @else
                                                                        None
                                                                    @endif
                                                                </td>
                                                                <td class="text-center"> 
                                                                    @if($transaction['transaction_type'] == \App\Models\UserWalletTransaction::TYPE_CREDIT )
                                                                        ${{ number_format($transaction['amount'], 2) }} 
                                                                    @else
                                                                        None
                                                                    @endif </td>
                                                                <td class="text-center">
                                                                    <div class="payment-success"> <i class="fas fa-check-circle"></i>Success </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($transaction['transaction_id'])
                                                                    <a class="btn btn-primary" href="{{ URL::to('invoicepdf',$transaction['transaction_id']) }}">Invoice PDF</a>
                                                                    @endif
                                                                </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5"> You have no transaction yet </td>
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                        @if( $url = $transactions->nextPageUrl() )
                                        <div class="white-btn d-flex align-items-center">
                                            <a href="{{$url}}"> Load More </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection