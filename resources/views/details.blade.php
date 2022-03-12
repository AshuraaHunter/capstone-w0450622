@extends('common')

@section('pagetitle')
{{ $item->title }} | Item Info
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')
        <div class="container pt-6 ps-6" style="margin-left: 20rem; margin-top: 7.5rem; width: 720px">
            <a href="#"><img class="card-img-top" style="max-height: 400px; max-width: 400px" src={{ Storage::url('images/items/lrg_'.$item->picture) }} alt=""></a>
        </div>

@endsection