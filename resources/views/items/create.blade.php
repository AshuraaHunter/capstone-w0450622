@extends('common') 

@section('pagetitle')
Create Item
@endsection

@section('pagename')
Laravel Project
@endsection

@section('scripts')
{!! Html::script('/bower_components/parsleyjs/dist/parsley.min.js') !!}
{!! Html::script('https://cdn.tiny.cloud/1/tg6yi464ynwd3ugih0n92s2wj7bm28auxnby0r9fvs1p8pgj/tinymce/5/tinymce.min.js') !!}
<script type="text/javascript">
	tinymce.init({
	  selector: 'textarea.tinymce-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
	  setup: function (editor) {
    	editor.on('change', function () {
            tinymce.triggerSave();
        });
      },
	  plugins: 'code table lists',
	  toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
	  content_css: '//www.tiny.cloud/css/codepen.min.css'
	});
</script>
@endsection

@section('css')
{!! Html::style('/css/parsley.css') !!}
@endsection

@section('content')
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h1>Add New Item</h1>
			<hr/>

			{!! Form::open(['route' => 'items.store', 'data-parsley-validate' => '', 
			                'files' => true]) !!}
			    
				{{ Form::label('title', 'Name:') }}
			    {{ Form::text('title', null, ['class'=>'form-control', 'style'=>'', 
			                                  'data-parsley-required'=>'', 
											  'data-parsley-maxlength'=>'255']) }}

				{{ Form::label('category_id', 'Category:', ['style'=>'margin-top:20px']) }}
				<select name='category_id' class='form-control' data-parsley-required="true">
					<option value="">Select Category</option>
					@foreach ($categories as $category)
						<option value='{{ $category->id }}'>{{ $category->name }}</option>
					@endforeach
				</select>

			    {{ Form::label('description', 'Description:', ['style'=>'margin-top:20px']) }}
			    {{ Form::textarea('description', null, ['class'=>'form-control tinymce-editor', 
				                                 'data-parsley-required'=>'']) }}

				{{ Form::label('price', 'Price:', ['style'=>'margin-top:20px']) }}
			    {{ Form::text('price', null, ['class'=>'form-control', 'style'=>'', 
			                                  'data-parsley-required'=>'']) }}

				{{ Form::label('quantity', 'Quantity:', ['style'=>'margin-top:20px']) }}
			    {{ Form::text('quantity', null, ['class'=>'form-control', 'style'=>'', 
											  'data-parsley-required'=>'']) }}
											  
				{{ Form::label('sku', 'SKU:', ['style'=>'margin-top:20px']) }}
			    {{ Form::text('sku', null, ['class'=>'form-control', 'style'=>'', 
											  'data-parsley-required'=>'']) }}
											  
				{{ Form::label('picture', 'Picture:', ['style'=>'margin-top:20px']) }}
			    {{ Form::file('picture', null, ['class'=>'form-control', 
				                                       'style'=>'',
													   'data-parsley-required'=>'']) }}

			    {{ Form::submit('Create Item', ['class'=>'btn btn-success btn-lg btn-block', 'style'=>'margin-top:20px']) }}

			{!! Form::close() !!}

		</div>
	</div>

@endsection