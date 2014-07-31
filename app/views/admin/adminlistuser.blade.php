@extends('adminlayout')

@section('content')
	<h2>List all admin users</h2>
	
	NOTE: users should be loaded on controller, not view... but hey...
	
	<ul class="list-group">
		@foreach ($users as $user)
    		<li class="list-group-item">{{ $user->username }}</li>
		@endforeach
	</ul>		
@stop
