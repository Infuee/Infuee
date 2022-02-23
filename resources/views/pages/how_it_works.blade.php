{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           

			<div class="how_it_works space_work bg-blue lastsection-space_b section-space">
                <div  class="container">

				    @php

				    $content = htmlspecialchars_decode(stripslashes(@$content->how_it_works)); 
				    if( Helpers::isWebview() ){
            		  $content = str_replace("How It Works", "",  $content) ;
                    }
                    echo $content ;
				    @endphp
                    
                </div>
            </div>

    
@endsection