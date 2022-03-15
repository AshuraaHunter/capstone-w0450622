@extends('common')

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
                        <button type="button" class="btn btn-success d-inline align-top"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

@endsection