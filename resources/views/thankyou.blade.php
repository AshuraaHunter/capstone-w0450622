@php
session_start();

if (!isset($_SESSION) || !isset($_SESSION["SESSION_ID"]) || !isset($_SESSION["SESSION_IPADDRESS"])) {
  header("Location: ". URL::to('/front'));
}
else {
  $session_id = $_SESSION["SESSION_ID"];
  $session_ipaddress = $_SESSION["SESSION_IPADDRESS"];
  #echo "session_id: $session_id, session_ipaddress: $session_ipaddress";
}
@endphp
@extends('public')

@section('pagetitle')
Thank you for your purchase!
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')

    @php
        $sum = 0;
    @endphp
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Thank you for your purchase!</h1>
                <p>Your items will be delivered shortly (if applicable).</p>
                <br />
                <br />
                <h4>Your Receipt:</h4>
            </div>
            <div class="col-md-12">
                <hr />
            </div>
        </div>

        <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <th>Quantity</th>
                        <th>Item</th>
                        <th>Price</th>
                    @foreach ($orderItems as $orderItem)
                        <tr>
                            <td style="max-width: 80px">
                                <h5 class="text-right float-right">{{ $orderItem->quantity }}</h5>
                            </td>
                            <td>
                                <h4 class="card-title truncate">
                                    <a href="{{ route('details', $orderItem->item_id) }}" class="truncate">{{ $items->firstWhere('id', '=', $orderItem->item_id)->title }}</a>
                                </h4>
                                <div class="container" style="width: 100%">
                                    <div class="row">
                                        <div class="col pt-2">
                                            <h5 class="text-left float-left">${{ $orderItem->price }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $sum += ($orderItem->price) * ($orderItem->quantity);
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <h5 class="text-right float-right">Subtotal:</h6>
            <h3 class="text-right float-right">${{ $sum }}</h4>
            <h5 class="text-right float-right">Total (tax inc.):</h6>
            <h3 class="text-right float-right">${{ number_format($sum * 1.15, 2) }}</h3>
            <br />
        </div>
    </div>
    @php
        unset($_SESSION["SESSION_ID"]);
        unset($_SESSION["SESSION_IPADDRESS"]);
    @endphp

@endsection