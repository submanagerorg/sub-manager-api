@extends('emails.template')

@section('subject')
    <h2 class=" ">Set Password</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="{{ asset('images/set-password.png') }}" alt="Set Password image" height="95%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        Your Payment of the sum of <span class="orange-text">#7800</span> for
        <span class="orange-text">Standard Plan (Yearly)</span> was successful. <br><br>
        Set a password for your account to proceed.
    </div>
@endsection

@section('button-link')
    <a href="#"><button class="btn btn-dark">Set Password</button></a>
@endsection

@section('url-link')
    <a href="https:/subsync.com/xxxxxx">https:/subsync.com/xxxxxx</a>
@endsection
