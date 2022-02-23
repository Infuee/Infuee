@php
$user = auth()->user();
@endphp
<header class="header-main common-header">
    <div class="header-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container-xl container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ Helpers::asset('images/infuee-logo.png') }}" alt="{{ env('APP_NAME') }}">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{Helpers::isMenuActive('jobs')}}" href="{{url('jobs')}}">Browse Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{Request::segment(1) == 'influencers' ? 'active' :'' }}" href="{{url('influencers')}}">Browse Influencers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{Helpers::isMenuActive('campaigns')}}" href="{{url('campaigns')}}">Campaign</a>
                        </li>
                        @if(!$user || $user && $user->isUser())
                        <li class="nav-item">
                            <a class="nav-link {{Request::segment(1) == 'be-influencer' ? 'active' :'' }}" href="{{url('be-influencer')}}">Become An Influencer</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{Request::segment(1) == 'how-it-works' ? 'active' :'' }}" href="{{url('how-it-works')}}">How It works</a>
                        </li>
                    </ul>
                    @if(@$user)

                    <div class="nav-right_btn d-flex">
                        
       
                            <a id="readnotify" href="{{ url('/notification') }}" class="btn notification-btn"> <img src="{{ Helpers::asset('images/bell-icon.png') }}" alt="">
                                
                                <span id="notificationCount_span">@if(Helpers::notificationCount() > 0) {{Helpers::notificationCount()}} @endif</span>
                            </a>

                            <div class="dropdown mydropdown_head">
                                <a class="btn dropdown-toggle user-btn" href="javascript:;" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ $user->getProfileImage() }}" alt="" style="width: 40px;border-radius: 50%;">
                                </a>

                                <ul class="dropdown-menu dropdown-menu-md-start dropdown-menu-lg-end dropdown-menu-xl-end" aria-labelledby="dropdownMenuLink">
                                    @if($user->isUser())<li><a class="dropdown-item" href="{{ url('cart') }}"> <img src="images/cart.png" alt=""> My Cart </a></li>@endif
                                    <li><a class="dropdown-item" href="{{ url('my-jobs') }}"> <img src="images/job.png" alt=""> My Job </a></li>
                                    <li><a class="dropdown-item" href="{{ url('my-campaigns') }}"> <img src="images/campaign.png" alt=""> My Campaign </a></li>
                                    @if($user->isInfluencer())
                                    <li><a class="dropdown-item" href="{{url('orders')}}"> <img src="images/order.png" alt=""> My Order </a></li>
                                    <li><a class="dropdown-item" href="{{url('bank-settings')}}"> <img src="images/bank.png" alt=""> Manage Bank Details </a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{url('message')}}"> <img src="images/message.png" alt=""> Message </a></li>
                                    <li><a class="dropdown-item" href="{{url('my-active-jobs')}}"> <img src="images/active-jobs.png" alt=""> My Active Jobs </a></li>
                                    <li><a class="dropdown-item" href="{{ url('my-profile') }}"> <img src="images/profile.png" alt=""> My Profile </a></li>
                                    <li><a class="dropdown-item" href="{{ url('wallet') }}"> <img src="images/wallets.png" alt=""> My wallet </a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{url('transactions')}}">  <img src="images/transaction.png" alt=""> Transactions </a></li>
                                    <li><a class="dropdown-item" href="{{ url('logout') }}"> <img src="images/logout.png" alt="">  Log Out </a></li>
                                </ul>
                            </div>

                        </div>

                    @else
                    <div class="nav-right_btn d-flex">
                        <a href="{{url('login')}}" class="btn login-btn">Login</a>
                        <a href="{{url('signup')}}" class="btn signup-btn">Signup</a>
                    </div>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</header>