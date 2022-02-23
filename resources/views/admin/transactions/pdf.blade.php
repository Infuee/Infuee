<!DOCTYPE html>
<html>
   <head>

      <style>
         @page{
            margin: 10px 25px;
            padding: 0;
         }
         body{
            font-family: DejaVu Sans; sans-serif;
         }
         .transaction_table tr th,.transaction_table tr td{
            text-align: left;
            padding: 0 2px;
            border:1px solid #555;
            font-size: 13px;
         }
         .transaction_table tr th{
            background-color: #ddd;
         }
         .transaction_table tr td{
            vertical-align: top;
         }
      </style>
   </head>
   <body>
      <div>
         <table style="width: 100%;" cellpadding="0" cellspacing="0">
            <tr>
               <td style="width: 50%;" align="left">
                  <?php //echo URL::to('/media/logos/logo.png'); die; 
                  // echo '<pre>'; print_r($Transaction->order); echo '</pre>';die;
                  ?>
                  <img style="width: 200px;" src="{{URL::to('/media/logos/logo.png')}}">
                  <!-- <img src="{{base_path().'/media/images/logos/logo.png'}}"> -->
                  <!-- <img src="https://infuee.softuvo.xyz/media/images/logo.png"> -->
               </td>
               <td style="width: 50%;" align="right">
                  <p style="font-size: 16px;">Tax Invoice/Bill of supply</p>
               </td>
            </tr>
         </table>
         <table style="width: 100%;padding-top: 20px;" cellpadding="0" cellspacing="0">
            <tr>
               <td colspan="2">
                  <h2 style="font-size: 18px; font-weight: 600;margin: 0 0 7px;">Billing Address :</h2>
                  <p style="margin:0; padding:0; font-size: 14px;">{{@$paymentIntent['charges']['data']['0']['billing_details']['name']}}</p>
                  <p style="margin:0; padding:0; font-size: 14px;">{{@$Transaction->user->email}}</p>
                  <p style="margin:0; padding:0; font-size: 14px;">{{@$paymentIntent['charges']['data']['0']['billing_details']['address']['line1']}}</p>
                  <p style="margin:0; padding:0; font-size: 14px;">{{@$paymentIntent['charges']['data']['0']['billing_details']['address']['country']}}, {{@$paymentIntent['charges']['data']['0']['source']['address_zip']}}</p>
               </td>
            </tr>
         </table>
         <table style="width: 100%;padding-top: 20px;" cellpadding="0" cellspacing="0">
            <tr>
               <td style="width: 50%;" align="left">
                  <p style="font-size: 14px;margin:0; padding:0;"><strong style="font-size: 13px;">Order Number : </strong>{{@$Transaction->order->order_id}}</p>
                  <p style="font-size: 14px;margin:0; padding:0;"><strong style="font-size: 13px;">Order Date : </strong>{{@$Transaction->order->created_at}}</p>
               </td>
               <td style="width: 50%;" align="right">
                  <p style="font-size: 14px;margin:0; padding:0;"><strong style="font-size: 13px;">Invoice Number : </strong> {{@$Transaction->transaction_no}}</p>
                  <p style="font-size: 14px;margin:0; padding:0;"><strong style="font-size: 13px;">Invoice Date : </strong> {{date('Y-m-d H:i:s',@$paymentIntent['charges']['data']['0']['created'])}}</p>
               </td>
            </tr>
         </table>

         <table style="width: 100%;padding-top: 20px;" cellpadding="0" cellspacing="0" class="transaction_table">
            <tr>
               <th style="width: 30px;">Sr. No.</th>
               <th style="width: 200px;">Plan Name</th>
               <th style="width: 200px;">Plan Category</th>
               <th style="width: 30px;">Price</th>
            </tr>
            @php 
               $i = 1;
            @endphp
            @foreach($Transaction->order_items AS $item)
            
            <tr>
               <td>{{$i}}</td>
               <td>{{$item->userPlan->allPlan->name}}</td>
               <td>{{$item->userPlan->allPlan->allCategory->name}}</td>
               <td>{{env('CURRENCY').$item->userPlan->price}}</td>
            </tr>
            @php 
               $i++;
            @endphp
            @endforeach
            
            <tr>
               <td colspan="3" valign="middle"><strong>TOTAL : </strong></td>
               <td style="background-color: #ddd;">{{env('CURRENCY').@$Transaction->order->total}}</td>
            </tr>            
            <tr>
               <td colspan="3" valign="middle"><strong>DISCOUNT : </strong></td>
               <td style="background-color: #ddd;">{{env('CURRENCY').@$Transaction->order->discount_price}}</td>
            </tr>            
            <tr>
               <td colspan="3" valign="middle"><strong>GRAND TOTAL : </strong></td>
               <td style="background-color: #ddd;">{{env('CURRENCY').(@$Transaction->order->total - @$Transaction->order->discount_price)}}</td>
            </tr>
         </table>
      </div>
   </body>
</html>


