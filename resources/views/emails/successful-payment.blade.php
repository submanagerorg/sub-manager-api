@extends('emails.template')

@section('subject')
    <h2 class=" ">Payment Successful</h2>
@endsection

@section('image')
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Your Payment of the sum of <span class="orange-text">#7800</span> for
            <span class="orange-text">Standard Plan (Monthly)</span> was successful. 
        </p>
        <br>
        <p>  Your plan is now active. </p>
        <!-- <br> -->
        <div class="text-left">
            <p>Plan: <span class="blue-text"> Standard Plan (Monthly)</span></p>
            <p>Payment Date: <span class="blue-text"> 01-March-2024 </span></p>
            <p>Expiration Date: <span class="blue-text"> 01-April-2024 </span></p>
            <p>Amount: <span class="blue-text"> #750</span></p>
            <p>Reference: <span class="blue-text"> TRF-SBNC-789RTR067PL </span></p>
        </div>
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
