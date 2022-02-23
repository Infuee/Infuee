@if ($message = Session::get('alert'))
    <div class="flash-message success_msg">
        <p>                        
            {{$message}}
        </p>
    </div>
@endif

                            

@if ($message = Session::get('success'))
<div class="flash-message success_msg">
        <p>                        
            {{$message}}                        
        </p>
    </div>
@endif


@if ($message = Session::get('error') )
<div class="flash-message error_msg">
    <p>                        
        {{$message}}
    </p>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="flash-message warining_msg">
    <p>                        
        {{ $message }}
    </p>
</div>
@endif


@if ($message = Session::get('info'))
<div class="flash-message info_msg">
    <p>                        
        {{ $message }}
    </p>
</div>
@endif


@if ($errors->any())
<div class="flash-message error_msg">
    <p>                        
        Please check the fields.
    </p>
</div>
@endif