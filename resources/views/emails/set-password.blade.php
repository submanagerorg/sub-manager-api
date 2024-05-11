@extends('emails.template')

@section('subject')
    <h2 class=" ">Set Password</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429067/subsyncassets/email_assets/ljujofnvcgkb6qge7mfu.png" alt="Set Password image" height="95%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        Your Payment of the sum of <span class="orange-text">{{$amount}}</span> for
        <span class="orange-text">{{$plan}}</span> was successful. <br><br>
        Set a password for your account to proceed.
    </div>
@endsection

@section('button-link')
    <a href="{{ $set_password_url }}"><button class="btn btn-dark">Set Password</button></a>
@endsection

@section('url-link')
    <a href="{{ $set_password_url }}">{{ $set_password_url }}</a>
@endsection
