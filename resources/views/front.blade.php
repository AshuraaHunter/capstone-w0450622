@php
session_start();

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
Front Page
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')
        <div class="row">
            <div class="col">
                <div class="flex-shrink-0 pt-3 bg-white" style="width: 280px">
                    <div class="d-flex pb-3 mb-3 align-items-center border-bottom">
                        <span class="h3" style="font-size: 2rem">Categories</span>
                    </div>
                    <div class="d-inline-block bg-white pl-1">
                        <ul class="list-group list-group-flush">
                            @foreach ($categories as $category)
                            <li class="list-group-item">
                                <a class="link-dark" href="{{ route('front', $category->id) }}" style="font-size: 1.75rem">{{ $category->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="container pt-4" style="width: 720px">
                    @foreach ($items->chunk(3) as $chunk)
                        <div class="row justify-content-center align-items-center">
                            @foreach ($chunk as $item)
                                <div class="col-lg-3 col-md-6 mb-4 ms-4 ps-3">
                                    <div class="card ps-3" style="width: 180px; height: 250px">
                                    <a href="{{ route('details', $item->id) }}"><img class="card-img-top" src={{ Storage::url('images/items/tn_'.$item->picture) }} alt=""></a>
                                        <div class="card-body">
                                            <h4 class="card-title truncate">
                                                <a href="{{ route('details', $item->id) }}" class="truncate">{{ $item->title }}</a>
                                            </h4>
                                            <div class="container" style="width: 100%">
                                                <div class="row">
                                                    <div class="col pt-2" style="width: 50%">
                                                        <h5 class="text-left float-left">${{ $item->price }}</h5>
                                                    </div>
                                                    <div class="col" style="width: 50%">
                                                        <!--<button type="button" class="btn btn-success float-right"><span class="glyphicon glyphicon-shopping-cart"></span> Buy Now</button>-->
                                                        <form action={{ route("add_to_cart") }} class="d-inline align-top" method="post" enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" value="{{ Crypt::encryptString($item->id) }}" name="item_id">
                                                            <input type="hidden" value="{{ Crypt::encryptString($session_id) }}" name="session_id">
                                                            <input type="hidden" value="{{ Crypt::encryptString($session_ipaddress) }}" name="ip_address">
                                                            <input type="hidden" value="1" name="quantity">
                                                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-shopping-cart"></span> Buy Now</button>
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col">
            </div>
        </div>

@endsection