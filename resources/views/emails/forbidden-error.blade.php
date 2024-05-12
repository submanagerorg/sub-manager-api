@extends('emails.template')

@section('subject')
    <h3 class=" ">Forbidden Error</h3>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715428965/subsyncassets/email_assets/serdojhqwu3kehe7brxl.png" alt="Forbidden Error image" height="85%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        Invalid or expired url provided.
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
