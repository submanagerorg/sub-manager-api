@extends('emails.template')

@section('subject')
    <h2 class=" ">Subscriptions Expiring Soon</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="{{ asset('images/reminder.png') }}" alt="Expiring soon image" height="100%">
    </div>
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Hello Stacey,
        </p>
        <p >
            This is a friendly reminder that <span class="red-text">three (3)</span> of
            your subscriptions will expire in <span class="red-text"><b>3 days</b></span>. 
        </p>
        <p >
            You can always view your account and manage your subscriptions on the app.
        </p>
        <p>
            Make sure to stay on top of your subscriptions!
        </p>
    </div>
    <br>
    <div class="button-container">
        <div class="row">
            <!-- Left Column -->
            <div class="col-xs-3 text-left sub-name">
                <p class="blue-text"><b>Spotify</b></p>
                <br>
                <p class="blue-text"><b>Amazon Prime</b></p>
                <br>
                <p class="blue-text"><b>Hulu</b></p>
            </div>
            <!-- Right Column -->

            <div class="col-xs-5 custom-column">
                <div>
                    <a href="#"><button class="btn-renew">Renew</button></a>
                </div>
                <br>
                <div>
                    <a href="#"><button class="btn-renew">Renew</button></a>
                </div>
                <br>
                <div>
                    <a href="#"><button class="btn-renew">Renew</button></a>
                </div> 
            </div>  
        </div >
    </div>
  <br>
@endsection

@section('button-link')
    <a href="#"><button class="btn btn-dark">View Subscriptions</button></a>
@endsection

@section('url-link')
    <a href="https:/subsync.com/xxxxxx">https:/subsync.com/xxxxxx</a>
@endsection
