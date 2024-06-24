@extends('emails.template')

@section('subject')
    <h2 class=" ">Subscription(s) Expiring Soon</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/w_500,f_auto,q_auto/v1715429058/subsyncassets/email_assets/l54wbnvxj9svasaonlzx.png" alt="Expiring soon image" height="100%">
    </div>
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Hello {{$username}},
        </p>
        <p >
            This is a friendly reminder that <span class="red-text">{{$subscription_count}}</span> of
            your subscriptions will expire in <span class="red-text"><b> {{$days_left}} day(s)</b></span>. 
        </p>
        <p >
            You can always view your account and manage your subscriptions on the app.
        </p>
        <p>
            Make sure to stay on top of your subscriptions!
        </p>
    </div>
    <br>

    <table class="center button-container">
        @foreach ($subscriptions as $subscription)
        <tr>
            <td>
                <span class="blue-text sub-text">{{ $subscription->name }}</span> - {{$subscription->currency->sign}}{{$subscription->amount}}
            </td>
            <td>
                <a href="{{ $subscription->service && $subscription->service->subscription_url ? $subscription->service->subscription_url : config('app.web_app_url') . '/subscriptions/renew/' . $subscription->uid }}">
                    <button class="btn-renew"><b>Renew</b></button>
                </a>
            </td>
        </tr>
        @endforeach
  </table>
  <br>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url')  .'/subscriptions' }}"><button class="btn btn-dark">View Subscriptions</button></a>
@endsection

@section('url-link')
<a href="{{ config('app.web_app_url')  .'/subscriptions' }}">{{ config('app.web_app_url') . '/subscriptions' }}</a>
@endsection
