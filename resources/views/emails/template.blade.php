<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/logo.png"  type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>SubSync</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body{
            margin: auto;
            font-size: 14px;
            font-family:'Poppins';
        }

        .centered-section {
            height: 100%; /* Set height to 100% of the viewport height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #fff;
        }

        .logo-img {
            max-width: 98px;
            height: auto;
        }

        .hero-heading {
            margin-top: 20px; /* Added margin to the heading for spacing */
        }
            
        .btn{
            padding: 1vh 1vw;
            border-radius: 6px;
            background-color: #042B4E;
            width: 98vw;
            max-width: 400px;
            text-align: center;
            font-size: 16px;
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
            align-self: center;
        }
        
        .content {
            margin-top: 10px;
            font-size: medium;
        }

        .content-container {
            width: 98vw;
            max-width: 400px;
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
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .button-container{
            width: 98vw;
            max-width: 400px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .code{
            font-weight: bold;
            margin-right: 10px;
            border: 3px solid #A1C3FC;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .btn-renew{
            margin-left: 35px;
            padding: 5px 20px;
            width: 48vw;
            max-width: 200px;
            border-radius: 6px;
            border: 0.1px solid;
            background-color: #fff;
            border-color:#042B4E ;
            color:#042B4E ;

        }

        .btn-renew:hover{
            border-radius: 6px;
            border: 3px solid #042B4E;
        }

        .sub-name p{
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .row .sub-name{
            max-width: 120px;
        }

    </style>
</head>

<body>
    <section class="centered-section hero-heading">
        <br> 

        <div class=" text-center mb-3 ">
            <img src="{{ asset('images/main-logo.png') }}" alt="logo" class="logo-img">
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

        <div class="center-btn centered-section">
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

    <!-- <div class="container-fluid">
    <div class="row"> -->
        <!-- Desktop View -->
        <!-- <div class="col-md-8 d-none d-md-block">
        </div> -->

        <!-- Mobile View -->
        <!-- <div class="col-12 d-block d-md-none">
        </div>
    </div>
    </div> -->


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>

</body>
</html>