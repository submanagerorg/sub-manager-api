@extends('emails.template')

@section('subject')
    <h3 class=" ">Service Payment Successful</h3>
@endsection

@section('image')

@endsection

@section('content')
    <div class="content-container">
        <p>You payment for {{$serviceName}} has been successfully processed.</p>
        <p>If you opted to track your subscription, you can check your dashboard for the newly added subscription.</p>
        <p>Thank you for using SubSync.</p>
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
