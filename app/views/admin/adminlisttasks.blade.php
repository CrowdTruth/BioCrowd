@extends('adminlayout')

@section('content')
	<h2>List all available tasks</h2>
	
	<ul class="list-group">
		@foreach ($tasks as $task)
    		<li class="list-group-item">{{ $task }}</li>
		@endforeach
	</ul>		
@stop
