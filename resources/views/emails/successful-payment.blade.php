@extends('emails.template')

@section('subject')
    <h2 class=" ">Payment Successful</h2>
@endsection

@section('image')
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Your Payment of the sum of <span class="orange-text">{{$amount}}</span> for
            <span class="orange-text">{{$plan}}</span> was successful. 
        </p>
        <br>
        <p>  Your plan is now active. </p>
        <br>
        <div class="text-left">
            <p>Plan: <span class="blue-text"> {{$plan}} </span></p>
            <p>Payment Date: <span class="blue-text"> {{ \Carbon\Carbon::parse($user_pricing_plan->start_date)->format('d-F-Y') }} </span></p>
            <p>Expiration Date: <span class="blue-text"> {{ \Carbon\Carbon::parse($user_pricing_plan->end_date)->format('d-F-Y') }} </span></p>
            <p>Amount: <span class="blue-text"> {{$amount}} </span></p>
            <p>Reference: <span class="blue-text"> {{$reference}} </span></p>
        </div>
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
