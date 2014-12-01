@extends('layout')

@section('extraheaders')
	<!-- Add any extra Javascript and CSS here -->
@stop

@section('content')
	<!-- Add the HTML content -->
	Game content -- perhaps this could be a blade template instead ?
	{{ Form::open( [ 'url' => 'submitGame', 'method' => 'POST' ] ) }}
		{{ Form::hidden('gameId', $gameId) }}
		{{ Form::submit('Submit', [ 'class' => 'form-horizontal', 'class' => 'btn btn-primary'] ) }}
	{{ Form::close() }}
@stop

@section('sidebar')
	@parent
	<!-- Add any content at the bottom of the sidebar -->
	<div class='col-xs-3 sidebar contentbox' style="height: 100px; width: 320px; color: #333; padding: 30px; background: white; text-align: center">
		Hello game sidebar
	</div>
@stop
