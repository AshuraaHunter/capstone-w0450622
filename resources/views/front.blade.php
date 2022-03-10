@extends('common')

@section('pagetitle')
Front Page
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')
        <div class="row">
            <div class="col">
                <div class="flex-shrink-0 bg-white" style="max-width: 25%">
                    <div class="d-flex pb-3 mb-3 align-items-center border-bottom">
                        <span class="h3">Categories</span>
                    </div>
                    <div class="d-inline-block bg-white pl-1">
                        <ul class="list-group list-group-flush">
                            @foreach ($categories as $category)
                            <li class="list-group-item">
                                <a class="link-dark" href="#">{{ $category->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="container pt-4">
                    @foreach ($items->chunk(3) as $chunk)
                        <div class="row justify-content-center align-items-center">
                            @foreach ($chunk as $item)
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="card h-100">
                                    <a href="#"><img class="card-img-top" src={{ Storage::url('images/items/tn_'.$item->picture) }} alt=""></a>
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <a href="#">{{ $item->title }}</a>
                                            </h4>
                                            <h5>${{ $item->price }}</h5>
                                            <button type="button" class="btn btn-success float-right"><span class="glyphicon glyphicon-shopping-cart"></span> Buy Now</button>
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