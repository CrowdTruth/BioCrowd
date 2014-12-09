@extends('admin.layout')

@section('content')
	<h2>List all admin users</h2>
	
	<ul class="list-group">
		@foreach ($users as $user)
    		<li class="list-group-item">{{ $user->username }}</li>
		@endforeach
	</ul>
@stop
