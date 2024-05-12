@extends('emails.template')

@section('subject')
    <h2 class=" ">Password Reset</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429043/subsyncassets/email_assets/j2i9odzsaehyasbricxx.png" alt="Password reset image" height="85%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        We received a request to reset the password for the SubSync
        account associated with this e-mail address. <br><br>
        If you did not make this request, you can safely ignore this email.
    </div>
@endsection

@section('button-link')
    <a href="{{ $reset_url }}"><button class="btn btn-dark">Reset Password</button></a>
@endsection

@section('url-link')
    <a href="{{ $reset_url }}">{{ $reset_url }}</a>
@endsection
