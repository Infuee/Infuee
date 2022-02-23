@extends('email.emailslayouts.header')
<body>
<div class="main_back">
    <div class="bgBody">
      <div class="banner"> 
          <div class="banner_img" style="">
              <!-- <img style="width: 100%; height: 250px;" src="banner.jpg"> -->
          </div>
          <div class="banner_content">
              <img style="width: 120px;" src="{{URL::to('/media/logos/logo.png')}}">
              <h3 style="color: #ffffff; text-transform: capitalize; font-size: 30px; /*font-family: 'Archivo Black'*/, sans-serif;">Order Placed</h3> 
          </div> 
      </div>
      <div class="content_div">
        <p>Hi {{@$influencer['first_name']}},</p>
        <p>{{@$user_info['first_name']}} {{@$user_info['last_name']}} has bought your plan. Please accept this order from your orders list.</p>
       

        <!-- <p>Still, have any questions regarding your account? Click <a href="" style="color: #000; font-weight: 600; text-decoration: none;">‘Reply’</a> to this email and we’ll be happy to help you.</a></p> -->
        <p>Thank you,</p>
        <p class="last">Infuee Team</p>
      </div>
@extends('email.emailslayouts.footer')