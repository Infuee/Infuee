<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head><base href="../../../../">
        <meta charset="utf-8" />
        {{-- Title Section --}}
        @php
            $page_name = Request::segment(1);
            if(empty($page_name)){
                $page_name = 'Home';
            }else{
                $page_name = str_replace("-"," ",$page_name);
            }
        @endphp
        <title>{{ config('app.name') }} | {{ ucwords($page_name) }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Basic -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Site Metas -->
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="/media/logos/favicon.png" />

        <!-- bootstrap core css -->
        <link rel="stylesheet" type="text/css" href="{{ Helpers::asset('css/frontend/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ Helpers::asset('css/frontend/select2.min.css') }}" />
        <!-- font awesome style -->
        <link href="{{ Helpers::asset('css/frontend/font-awesome.min.css') }}" rel="stylesheet" />
        <!-- slick -->
        <link href="{{ Helpers::asset('css/frontend/slick.css') }}" rel="stylesheet" />
        <link href="{{ Helpers::asset('css/frontend/slick-theme.css') }}" rel="stylesheet" />

        <link href="{{ Helpers::asset('css/dropify.css') }}" rel="stylesheet" />
        <link href="{{ Helpers::asset('css/dropzone.css') }}" rel="stylesheet" />
        <link href="{{ Helpers::asset('css/datepicker.css') }}" rel="stylesheet" />
        <link href="{{ Helpers::asset('css/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="{{ Helpers::asset('css/frontend/jquery-ui.css') }}" rel="stylesheet" />
        
        <!-- Custom styles for this template -->
        <link href="{{ Helpers::asset('css/frontend/style.css') }}" rel="stylesheet" />
        <link href="{{ Helpers::asset('css/frontend/backgrounds.css') }}" rel="stylesheet" />
        <!-- <link href="{{ Helpers::asset('css/frontend/style.scss') }}" rel="stylesheet" /> -->
        <!-- responsive style -->
        <link href="{{ Helpers::asset('css/frontend/responsive.css') }}" rel="stylesheet" />
        <!-- <link href="{{ Helpers::asset('css/frontend/responsive.scss') }}" rel="stylesheet" /> -->
        <!-- Font-Awesome5 -->
        <script src="{{ Helpers::asset('js/frontend/fontawesome.js') }}" crossorigin="anonymous"></script>
        
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//js.pusher.com/3.1/pusher.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script src="//cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
        <script src="{{ Helpers::asset('ckeditor/ckeditor.js') }}" crossorigin="anonymous"></script>
        <script src="{{ Helpers::asset('ckfinder/ckfinder.js') }}" crossorigin="anonymous"></script>

        






          

        @php
        $oldPages = ['cart', 'transactions', 'orders', 'bank-settings', 'profile-settings'] ;
        @endphp
        @if( in_array( \Request::segment(1), $oldPages ) )
            <link href="{{ Helpers::asset('css/frontend/oldstyle.css') }}" rel="stylesheet" />
            <link href="{{ Helpers::asset('css/frontend/oldresponsive.css') }}" rel="stylesheet" />
        @endif



    </head>
    <body class="{{Helpers::isWebview() ? 'web_view' : ''}}">
        
        @if(!Helpers::isWebview())
            @include('layout.partials.header')
        @endif
<!-- LOADER -->
<div class="justify-content-center align-items-center loader-wrapper loader" style="display:none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
        @yield('content')
        
        @if(!Helpers::isWebview())
            @include('layout.partials.footer')
        @endif

        @include('layout.partials.variables')

    <script>var HOST_URL = "{{url('/')}}";</script>
    <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>


    <script src="{{ Helpers::asset('js/frontend/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ Helpers::asset('js/frontend/jquery-ui.js')}}"></script>
    <script src="{{ Helpers::asset('js/jquery.validate.min.js')}}"></script>
    
    <!-- popper js -->
    <script src="{{ Helpers::asset('js/frontend/popper.min.js')}}"></script>
    <!-- bootstrap js -->
    <script src="{{ Helpers::asset('js/frontend/bootstrap.min.js')}}"></script>
    <script src="{{ Helpers::asset('js/frontend/select2.min.js')}}"></script>
    <script src="{{ Helpers::asset('js/frontend/slick.min.js')}}"></script>
    <script src="{{ Helpers::asset('js/bootstrap-datepicker.js')}}"></script>
    <script src="{{ Helpers::asset('js/sweetalert2.all.min.js')}}"></script>

    <script src="{{ Helpers::asset('js/dropify.min.js')}}" type="text/javascript"></script>
    <script src="{{ Helpers::asset('js/dropify.js')}}" type="text/javascript"></script>
    <script src="{{ Helpers::asset('js/dropzone.js')}}" type="text/javascript"></script>
    <script src="{{ Helpers::asset('js/payform.js')}}" type="text/javascript"></script>
    <script src="{{ Helpers::asset('js/ckeditor.js')}}" type="text/javascript"></script>
    <script src="{{ Helpers::asset('js/pages/custom/login/login-general.js')}}" type="text/javascript"></script>

    <script src="{{ Helpers::asset('js/frontend/custom.js')}}"></script>

    @if( \Request::segment('1') == 'message' )
        <script nonce="2726c7f26c" src="{{ Helpers::asset('js/socket.io.min.js') }}"></script>
        <script nonce="2726c7f26c" src="{{ Helpers::asset('js/socket.io-file-client.min.js') }}"></script>
        <script nonce="2726c7f26c" src="{{ Helpers::asset('js/chat.js') }}"></script>
    @endif

    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=intiallizeAutocomplete" async defer></script>


    @include('tosts')

    </body>
</html>