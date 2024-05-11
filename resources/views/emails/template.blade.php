<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SubSync</title>
    <link rel="stylesheet" href="{{ asset('fonts/Poppins/Poppins-Regular.ttf') }}">
    <style>
        body{
            margin: auto;
            font-size: 14px;
            background-color: #fff;
            font-family: 'Poppins', Arial, sans-serif;
            text-align: center;
        }

        .centered-section {
            height: 100%;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            text-align: center;
        }

        .logo-img {
            max-width: 98px;
            height: auto;
        }

        .hero-heading {
            margin-top: 20px; 
        }
            
        .btn {
            padding: 1vh 1vw;
            border-radius: 6px;
            background-color: #042B4E;
            color: #fff;
            width: 98vw;
            max-width: 400px;
            text-align: center;
            font-size: 16px;
            display: inline-block;
        }


        .btn:hover {
            background-color: #024174;
        }

        .orange-text{
            color: #FC992B;
        }

        .blue-text{
            color: #07569B;
        }

        .red-text{
            color: #DB4A45;
        }

        .content-image {
            height: 30vh;
            width: 60vw;
            margin: 0 auto; 
        }
        
        .content {
            margin-top: 10px;
            font-size: medium;
        }

        .content-container {
            width: 98vw;
            max-width: 400px;
            margin: 0 auto; 
            text-align: center;
        }

        .content-container a{
            word-wrap: break-word;
        }

        .heading {
            margin-top: 33px;
            font-size: large;
        }

        button a {
            text-decoration: none;
            color: inherit;
        }

        button a:hover {
            text-decoration: none;
            color: inherit;
        }

        .code-container{
            width: 98vw;
            max-width: 400px;
            margin: 0 auto;
        }

        .button-container{
            width: 98vw;
            max-width: 400px;
            margin: 0 auto;
        }

        .code{
            font-weight: bold;
            margin-right: 10px;
            border: 3px solid #A1C3FC;
            border-radius: 5px;
            padding: 5px 10px;
        }


        .btn-renew {
            padding: 8px 10px;
            border-radius: 6px;
            background-color: #fff;
            color:#042B4E ;
            display: inline-block;
            border: 1px solid #042B4E;
        }

        .btn-renew:hover{
            background-color: #024174;
            color: #fff;
        }

        .button-container table {
            width: 30%;
            border-collapse: collapse;
        }

        .button-container table.center {
            margin-left: auto;
            margin-right: auto;
        }

        .button-container th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .button-container th {
            background-color: #f2f2f2;
        }

        .text-left {
            text-align: left;
        }

        .sub-text {
            font-size: medium;
        }

    </style>
</head>

<body>
    <section class="centered-section">
        <div class=" text-center mb-3 ">
            <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429003/subsyncassets/email_assets/gy0mypzxprenm9qily0b.png " alt="logo" class="logo-img">
        </div>
        <div class="text-center heading">
            @yield('subject')
        </div>
  
        <br>  

        @yield('image')
        
        <div class="text-center content">
            @yield('content')
        </div>

        <br>

        <div class="center-btn">
            @yield('button-link')
        </div>

        <br>

        <div class="text-center content-container">
            <p>
                or copy and paste this link into your browser
            </p>
            @yield('url-link')
            <br><br>
        </div>

        <div class="footer">
            <p><a href="{{ config('app.website_url') }}">SubSync</a></p>
        </div>
    </section>
</body>
</html>