@extends('emails.template')

@section('subject')
    <h2 class=" ">Wallet Funding Successful</h2>
@endsection

@section('image')
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Your wallet has been successfully funded with the amount of <span class="orange-text">{{$amount}}</span>.
        </p>
        <p>  The details are shown below: </p>
        <div class="text-left">
            <p>Reference: <span class="blue-text"> {{$reference}} </span></p>
            <p>Amount: <span class="blue-text"> {{$amount}} </span></p>
            <p>Description: <span class="blue-text"> {{$description}} </span></p>
            <p>Transaction Date & Time: <span class="blue-text"> {{$dateTime}} </span></p>
            <p>Current Balance: <span class="blue-text"> {{$balance}} </span></p>
        </div>
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url') }}"><button class="btn btn-dark">Open Web App</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.web_app_url') }}">{{ config('app.web_app_url') }}</a>
@endsection
