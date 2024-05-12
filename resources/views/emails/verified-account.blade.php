@extends('emails.template')

@section('subject')
    <h3 class=" ">Account Verified</h3>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429028/subsyncassets/email_assets/bqjqtb5u3z1fztqq1bsl.png" alt="Verified image" height="100%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        Your email has been verified. <br>
        Thank you for choosing SubSync.
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
