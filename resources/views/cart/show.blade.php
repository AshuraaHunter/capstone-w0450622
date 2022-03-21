@extends('common') 

@section('pagetitle')
View Cart
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h1>Current Cart</h1>
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
					@foreach ($userCart as $cartItem)
						<tr>
							<div class="card ps-3" style="width: 540px; height: 250px">
                                <a href="{{ route('details', $cartItem->item_id) }}"><img class="card-img-top" style="max-width: 17.5em" src={{ Storage::url('images/items/tn_'.$items->firstWhere('id', '=', $cartItem->item_id)->picture) }} alt=""></a>
                                <div class="card-body">
                                    <h4 class="card-title truncate">
                                        <a href="{{ route('details', $cartItem->id) }}" class="truncate">{{ $items->firstWhere('id', '=', $cartItem->item_id)->title }}</a>
                                    </h4>
                                    <div class="container" style="width: 100%">
                                        <div class="row">
                                            <div class="col pt-2">
                                                <h5 class="text-left float-left">${{ $items->firstWhere('id', '=', $cartItem->item_id)->price }}</h5>
                                                <h5 class="text-right float-right">{{ $cartItem->quantity }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection