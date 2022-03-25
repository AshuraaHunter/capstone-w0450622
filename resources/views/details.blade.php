@php
try {
    session_start();
} catch (ErrorException $e) {

}

if (!isset($_SESSION) || !isset($_SESSION["SESSION_ID"]) || !isset($_SESSION["SESSION_IPADDRESS"])) {
  $session_id = session_id();
  $session_ipaddress = $_SERVER['REMOTE_ADDR'];
  #echo "session_id: $session_id, session_ipaddress: $session_ipaddress";
}
else {
  $session_id = $_SESSION["SESSION_ID"];
  $session_ipaddress = $_SESSION["SESSION_IPADDRESS"];
  #echo "session_id: $session_id, session_ipaddress: $session_ipaddress";
}
@endphp
@extends('public')

@section('pagetitle')
{{ $item->title }} | Item Info
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')
        <div class="container pt-6 ps-6" style="margin-left: 20rem; margin-top: 7.5rem; width: 720px">
            <div class="row">
                <div class="col">
                    <a href="#"><img class="card-img-top" style="max-height: 400px; max-width: 400px" src={{ Storage::url('images/items/lrg_'.$item->picture) }} alt=""></a>
                </div>
                <div class="col">
                    <div class="float-start">
                        <p class="text-muted d-inline">ID #{{ $item->id }}
                    </div>
                    <div class="float-end">
                        <p class="text-muted d-inline">SKU {{ $item->sku }}</p>
                    </div>
                    <h2 class="pt-3 mt-3">{{ $item->title }}</h1>
                    @php
                        echo($item->description)
                    @endphp
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <p><strong>{{ $item->quantity }}</strong> left in stock.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="float-end">
                        <p class="d-inline me-1" style="font-size: 1.8em"><strong>${{ $item->price }} • </strong></p>
                        <form action={{ route("add_to_cart") }} class="d-inline align-top" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ Crypt::encryptString($item->id) }}" name="item_id">
                            <input type="hidden" value="{{ Crypt::encryptString($session_id) }}" name="session_id">
                            <input type="hidden" value="{{ Crypt::encryptString($session_ipaddress) }}" name="ip_address">
                            <input type="hidden" value="1" name="quantity">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</button>
                        </form>
                        
                        <!--<button type="button" class="btn btn-success d-inline align-top"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</button>-->
                    </div>
                </div>
            </div>
        </div>

@endsection