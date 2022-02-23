<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #123</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice table {
            margin: 15px;
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
        table.invoice_pdf{
           font-family: arial, sans-serif;
           border-collapse: collapse;
           width: 100%;
           }

        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }
        tr:nth-child(even) {
        background-color: #dddddd;
        }
    </style>
</head>
<body>

<div class="information">
     <img src="http://infuee.local/images/infuee-logo.png?v=1626941711" alt="Logo" width="64" class="logo" style="text-align: center;" />
</div>


<br/>

<div class="invoice">
    <h3>Invoice specification </h3>
    <table width="100%" class="invoice_pdf">
        <thead>
        <tr>
            <th>Name</th>
            <th>Job Amount</th>
            <th>Job Name</th>
            <th>Total Wallet Amount</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            
            <td align="center">{{$influncerUser['first_name']}} {{$influncerUser['first_name']}}</td>
            <td align="center">${{@$proposal['cost']}}</td>
            <td align="center">{{@$userTo->title}}</td>
            <td align="center">{{number_format($influencersWalletAmount,2)}}</td>
            <td align="center">Success</td>

        </tr>
        
        </tbody>

    </table>
</div>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                Company Infuee
            </td>
        </tr>

    </table>
</div>
</body>
</html>