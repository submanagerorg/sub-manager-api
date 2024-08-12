@extends('emails.template')

@section('subject')
    <h3 class=" ">Welcome To SubSync</h3>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429013/subsyncassets/email_assets/cdrvkxbkhfw05oqazffj.png" alt="Welcome image" height="100%">
    </div>
@endsection

@section('content')
    <div class="content-container">
        Welcome! We're excited that you joined SubSync. <br><br>
        With SubSync, you will effortlessly manage all your subscriptions
        and receive timely renewal reminders, keeping you in control of your
        digital spending. <br><br>
        We're here to support you all the way!
    </div>
@endsection

@section('button-link')
    <a href="{{ config('app.chrome_extension_url') }}"><button class="btn btn-dark">Download Extension</button></a>
@endsection

@section('url-link')
    <a href="{{ config('app.chrome_extension_url') }}">{{ config('app.chrome_extension_url') }}</a>
@endsection

