@extends('emails.template')

@section('subject')
    <h3 class=" ">Service Payment Failed</h3>
@endsection

@section('image')

@endsection

@section('content')
    <div class="content-container">
        <p>You payment for {{$serviceName}} has been failed to process.</p>
        <p>The full amount has been reversed to your wallet.</p>
        <p>You may try again at a later time.</p>
        <p>Thank you for using SubSync.</p>
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
