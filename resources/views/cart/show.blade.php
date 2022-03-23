@extends('common') 

@section('pagetitle')
View Cart
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
						@php
							$fieldName = "quantity{$cartItem->id}";
						@endphp
						<tr>
							<td>
								<a href="{{ route('details', $cartItem->item_id) }}"><img class="card-img-top" style="max-width: 17.5em" src={{ Storage::url('images/items/tn_'.$items->firstWhere('id', '=', $cartItem->item_id)->picture) }} alt=""></a>
							</td>
							<td>
								<h4 class="card-title truncate">
									<a href="{{ route('details', $cartItem->item_id) }}" class="truncate">{{ $items->firstWhere('id', '=', $cartItem->item_id)->title }}</a>
								</h4>
								<div class="container" style="width: 100%">
									<div class="row">
										<div class="col pt-2">
											<h5 class="text-left float-left">${{ $items->firstWhere('id', '=', $cartItem->item_id)->price }}</h5>
											<form id='myform' class='identify' method='POST' action='#'>
												<input type='button' value='-' class='qtyminus' field='{{$fieldName}}' />
												<input type='text' name='{{$fieldName}}' value={{ $cartItem->quantity }} max={{ $items->firstWhere('id', '=', $cartItem->item_id)->quantity }} class='qty' />
												<input type='button' value='+' class='qtyplus' field='{{$fieldName}}' />
											</form>
											<!--<h5 class="text-right float-right">{{ $cartItem->quantity }}</h5>-->
										</div>
									</div>
								</div>
							</td>
						</tr>
						@php
							$sum += ($items->firstWhere('id', '=', $cartItem->item_id)->price) * ($cartItem->quantity);
						@endphp
					@endforeach
				</tbody>
			</table>
			<h5 class="text-right float-right">Subtotal:</h5>
			<h3 class="text-right float-right">${{ $sum }}</h3>
		</div>
	</div>

@endsection

<!-- source: http://jsfiddle.net/laelitenetwork/puJ6G/ -->
@section('scripts')
<script type="text/javascript">
jQuery(document).ready(function(){
    // This button will increment the value
    $('.qtyplus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
		var lim = parseInt($('input[name='+fieldName+']').attr('max'));
        // If is not undefined
        if (!isNaN(currentVal) && currentVal < lim) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
    // This button will decrement the value till 0
    $(".qtyminus").click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
});
</script>
@endsection