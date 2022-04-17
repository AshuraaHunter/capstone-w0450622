@extends('common') 

@section('pagetitle')
Item List
@endsection

@section('pagename')
Laravel Project
@endsection

@section('content')
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h1>Completed Orders</h1>
		</div>
		<div class="col-md-12">
			<hr />
		</div>
	</div>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<table class="table">
				<thead>
					<th>ID #</th>
					<th>Completed at</th>
					<th></th>
				</thead>
				<tbody>
					@foreach ($orders as $order)
						<tr>
							<th>{{ $order->id }}</th>
							<td style="width: 100px;">{{ date('M j, Y', strtotime($order->created_at)) }}</td>
							<td>
								{!! Form::open(['route' => ['thankyou', Crypt::encryptString($order->id)]]) !!}
							    	{{ Form::submit('View', ['class'=>'btn btn-md btn-success float-end', 'style'=>'']) }}
								{!! Form::close() !!}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection