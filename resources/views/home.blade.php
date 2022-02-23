<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ADMIN LOGIN</title>

        <!-- Fonts -->
        <link rel="shortcut icon" href="{{ asset('/images/favicon.png')}}" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                background-color: rgba(0,0,0,0.7);
                padding: 20px;
                color: #fff;
                text-align: center;
                border-radius:5px;
            }

            .content h1 {
                font-weight: bold;
                font-family: arial;
            }

            .content .btn {
                border: none;
                padding: 12px 15px;
                border-radius: 5px;
                font-weight: bold;
                letter-spacing: 1px;
                background-color: #e5f3fe;
                color: #0996ce;
                cursor: pointer;
            }


            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            
            .banner-home {
                    position: relative;
                    background-color: #343a40;
                    background: url(/images/auth/loginWall.jpg) no-repeat center center;
                    background-size: contain;
                    background-position: center center;
                }


        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height banner-home">
            <div class="content"><h1>WELCOME TO ONEREALM PORTAL</h1>
                <a href="{{url('admin')}}"><button type="button" class="btn">ADMIN LOGIN</button></a>                
            </div>
        </div>
    </body>
</html>
