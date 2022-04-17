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
										</div>
									</div>
								</div>
							</td>
							<td style="max-width: 80px">
								<!--
								<form id='myform' class='identify' method='POST' action='#'>
									<input type='button' value='-' class='qtyminus' field='{{$fieldName}}' />
									<input type='text' name='{{$fieldName}}' value={{ $cartItem->quantity }} max={{ $items->firstWhere('id', '=', $cartItem->item_id)->quantity }} class='qty' />
									<input type='button' value='+' class='qtyplus' field='{{$fieldName}}' />
								</form>
								-->
								<form action={{ route("update_cart") }} method="post" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="PUT">
									<input type="hidden" value="{{ Crypt::encryptString($cartItem->id) }}" name="id">
									<input type="hidden" value="{{ Crypt::encryptString($cartItem->item_id) }}" name="item_id">
									<input type="hidden" value="{{ Crypt::encryptString($session_id) }}" name="session_id">
									<input type="hidden" value="{{ Crypt::encryptString($session_ipaddress) }}" name="ip_address">
									<input type='button' value='-' class='qtyminus' field='{{$fieldName}}' />
									<input type='text' name='{{$fieldName}}' value={{ $cartItem->quantity }} max={{ $items->firstWhere('id', '=', $cartItem->item_id)->quantity }} class='qty' />
									<input type='button' value='+' class='qtyplus' field='{{$fieldName}}' />
									<button type="submit" class="btn btn-success d-inline align-top">Update</button>
								</form>
								<!--<h5 class="text-right float-right">{{ $cartItem->quantity }}</h5>-->
							</td>
							<td>
								<form action={{ route("remove_item") }} class="d-inline float-start" method="post" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" value="{{ Crypt::encryptString($cartItem->item_id) }}" name="item_id">
									<input type="hidden" value="{{ Crypt::encryptString($session_id) }}" name="session_id">
									<input type="hidden" value="{{ Crypt::encryptString($session_ipaddress) }}" name="ip_address">
									<button type="submit" class="btn btn-danger">Remove</button>
								</form>
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
			<br />
			{!! Form::open(['route' => 'check_order', 'data-parsley-validate' => '', 
			            	'method' => 'post', 'files' => true]) !!}
			    
				{{ Form::label('fname', 'First Name:') }}
				{{ Form::text('fname', null, ['class'=>'form-control', 'style'=>'', 
			                                	'data-parsley-required'=>'', 
												'data-parsley-maxlength'=>'127']) }}

				{{ Form::label('lname', 'Last Name:') }}
				{{ Form::text('lname', null, ['class'=>'form-control', 'style'=>'', 
			                                	'data-parsley-required'=>'', 
												'data-parsley-maxlength'=>'127']) }}

				{{ Form::label('phone', 'Phone Number:', ['style'=>'margin-top:20px']) }}
				{{ Form::text('phone', null, ['class'=>'form-control', 'style'=>'', 
			                                	'data-parsley-required'=>'']) }}

				{{ Form::label('email', 'Email Address:') }}
				{{ Form::text('email', null, ['class'=>'form-control', 'style'=>'', 
			                                	'data-parsley-required'=>'',
												'data-parsley-pattern'=>'/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/']) }} <!-- source: https://thisinterestsme.com/php-email-regex/ -->


				{{ Form::hidden('session_id', Crypt::encryptString($session_id)), ['class'=>'form-control', 'style'=>'', 'data-parsley-required'=>''] }}
				{{ Form::hidden('ip_address', Crypt::encryptString($session_ipaddress)), ['class'=>'form-control', 'style'=>'', 'data-parsley-required'=>''] }}
				{{ Form::submit('Checkout', ['class'=>'btn btn-success btn-lg btn-block', 'style'=>'margin-top:20px']) }}

			{!! Form::close() !!}
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