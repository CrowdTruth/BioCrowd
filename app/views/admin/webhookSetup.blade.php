@extends('admin.layout')

@section('content')
<h2>Export to file</h2>

<div id="collapseOne" class="panel-collapse collapse in">
	<div class="panel-body">

		{{ Form::open([ 'action' => 'DataportController@webhookUpdate' ]) }}
		{{ Form::hidden('action', 'none', [ 'id' => 'action' ]) }}
		<div class="form-group">
			{{ Form::label('url', 'Webhook URL:', [ 'class' => 'col-sm-2 control-label'] ) }}
			<div class="col-sm-4">
				{{ Form::text('webhook', $webhook, [ 'class' => 'form-control' ]) }}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-4">
				{{ Form::submit('Update', [ 'onclick' => 'doUpdate()' ] ) }}
				{{ Form::submit('Call'  , [ 'onclick' => 'doCall()' ] ) }}
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>

<script>
	function doUpdate() {
		$('#action').val('update');
	}

	function doCall() {
		$('#action').val('call');
	}
</script>
@stop
