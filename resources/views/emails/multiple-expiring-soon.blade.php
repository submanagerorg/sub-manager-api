@extends('emails.template')

@section('subject')
    <h2 class=" ">Subscriptions Expiring Soon</h2>
@endsection

@section('image')
    <div class="text-center mb-3 content-image">
        <img src="https://res.cloudinary.com/dwn7phnpa/image/upload/w_500,f_auto,q_auto/v1715429058/subsyncassets/email_assets/l54wbnvxj9svasaonlzx.png" alt="Expiring soon image" height="100%">
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

    <table class="center button-container">
      <tr>
          <td>
              <span class="blue-text sub-text">Spotify</span> - N4800
          </td>
          <td>
              <button class="btn-renew"><b>Renew</b></button>
          </td>
      </tr>
      <tr>
          <td>
              <span class="blue-text sub-text">Amazon Prime</span> - N9000
          </td>
          <td>
              <button class="btn-renew"><b>Renew</b></button>
          </td>
      </tr>
      <tr>
          <td>
              <span class="blue-text sub-text">Hulu</span> - N7200
          </td>
          <td>
              <button class="btn-renew"><b>Renew</b></button>
          </td>
      </tr>
  </table>
  <br>
@endsection

@section('button-link')
    <a href="#"><button class="btn btn-dark">View Subscriptions</button></a>
@endsection

@section('url-link')
    <a href="https:/subsync.com/xxxxxx">https:/subsync.com/xxxxxx</a>
@endsection
