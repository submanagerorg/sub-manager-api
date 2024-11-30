@extends('emails.template')

@section('subject')
    <h2 class=" ">Subscription(s) Expiring Today</h2>
@endsection

@section('image')
    
@endsection

@section('content')
    <div class="text-left content-container">
        <p>
            Hello {{$username}},
        </p>
        <p >
            This is a friendly reminder that <span class="red-text">{{$subscription_count}}</span> of
            your subscriptions expire <span class="red-text"><b>today</b></span>!
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
                <a href="{{ $subscription->service && $subscription->service->subscription_url ? $subscription->service->subscription_url : config('app.web_app_url') . '/renew-subscription/' . $subscription->uid }}">
                    <button class="btn-renew"><b>Renew</b></button>
                </a>
            </td>
        </tr>
        @endforeach
    </table>
  <br>
@endsection

@section('button-link')
    <a href="{{ config('app.web_app_url')  .'/all-subscriptions' }}"><button class="btn btn-dark">View Subscriptions</button></a>
@endsection

@section('url-link')
<a href="{{ config('app.web_app_url')  .'/all-subscriptions' }}">{{ config('app.web_app_url') . '/all-subscriptions' }}</a>
@endsection
