{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 




     <style>

      .success-outer{
        display: flex;
        flex-direction: ;
        align-items: center;
        height:  100vh;
         text-align: center;
        padding: 40px 15px;
        background: #EBF0F5;
      }
  
    .success-card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
       .success-card h1 {
          color: #88B04B;
          font-weight: 900;
          font-size: 40px;
          margin: 20px auto 10px;
        }
        .success-card p {
          color: #404F5E;
          font-size:20px;
          margin: 0;
        }
   
      .check-success{
        border-radius:200px;
        height:200px;
        width:200px; 
        background: #F8FAF5; 
        margin:0 auto;
      }
      .check-success i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
    </style>
    <body>



<div class="success-outer">
      <div class="success-card">
      <div class="check-success">
        <i class="success-checkmark">✓</i>
      </div>
        <h1>Success</h1> 
      </div>
</div>







@endsection