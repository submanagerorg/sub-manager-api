@extends('emails.template')

@section('subject')
    <h2 class=" ">Subscription Expiring Soon</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429058/subsyncassets/email_assets/l54wbnvxj9svasaonlzx.png" alt="Expiring soon image" height="100%">
    </div>
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Hello Stacey,
        </p>
        <p >
            This is a friendly reminder that your subscription to <span class="blue-text"><b>Spotify</b></span> 
            will expire in <span class="red-text"><b>3 days</b></span>. 
        </p>
        <p>
            You can always view your account and manage your subscriptions on the app.
        </p>
        <p >
            Make sure to stay on top of your subscriptions!
        </p>
    </div>
@endsection

@section('button-link')
    <a href="#"><button class="btn btn-dark">Renew Subscription</button></a>
    <br>
    <a href="#"><button class="btn btn-dark">View Subscription</button></a>
@endsection

@section('url-link')
    <a href="https:/subsync.com/xxxxxx">https:/subsync.com/xxxxxx</a>
@endsection
