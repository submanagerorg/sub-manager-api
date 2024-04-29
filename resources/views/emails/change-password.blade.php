@extends('emails.template')

@section('subject')
    <h2 class=" ">Password Reset</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="{{ asset('images/password-change.png') }}" alt="Password reset image" height="85%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        You have successfully updated your password and it works like a charm!<br><br>
        If you did not make this update, quickly reach out to us via <b>{{ config('mail.from.address') }}</b> 
        for urgent action.
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
