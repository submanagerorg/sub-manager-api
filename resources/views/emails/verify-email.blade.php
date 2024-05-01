@extends('emails.template')

@section('subject')
    <h3 class=" ">Verify Email Address</h3>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="{{ asset('images/verify-email.png') }}" alt="Verify Email image" height="100%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        Please confirm that you want to use
        this as your SubSync account email address. 
    </div>
@endsection

@section('button-link')
    <a href="{{ $verification_url }}"><button class="btn btn-dark">Verify Email</button></a>
@endsection

@section('url-link')
    <a href="{{ $verification_url }}">{{ $verification_url }}</a>
@endsection
