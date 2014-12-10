@extends('admin.layout')

@section('extraheaders')
<script>
	@if(is_null($game))
	function changeExtrasDiv(gameType) {
		divHtml = '';
		switch(gameType) {
		@foreach($gameTypeDivs as $id => $div)
			case "{{ $id }}": divHtml = "{{ $div }}"; break;
		@endforeach
		}
		$('#extraInfo').html(divHtml);
	}
	@endif

	// Add extra information (instructions, task list) to the form prior to submit
	function doSubmit() {
		// Copy input from instructionsInput DIV to instructions form field
		$('#instructions').val($('#instructionsInput').html());

		tasks = [];
		$('#taskList li').each(function() {
			tasks.push($(this).text());
		});
		tasks = JSON.stringify(tasks);
		$('#tasks').val(tasks);
	}

	// Add task data from text field to task list.
	function addTask() {
		taskText = $('#newTask').val();
		appendTask(taskText);
		$('#newTask').val('');
	}

	// Add the given data to the task list.
	function appendTask(taskText) {
		$('#taskList').append('<li class="list-group-item">' + taskText + '</li>');
	}
</script>
@stop

@section('content')
	<h2>Edit game</h2>

	<div class="panel-body">
		{{ Form::open([ 'action' => 'AdminController@editGameAction' , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' ] ) }}
			{{ Form::hidden('id', $game['id']) }}
			<div class="form-group">
				{{ Form::label('game_type', 'Game type:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4">
				@if(is_null($game))
					{{ Form::select('game_type', $gameTypes, $game['game_type'], [ 'class' => 'form-control' ]) }}
				@else
					{{ Form::hidden('game_type', $game['game_type']) }}
					{{ $gameTypes[$game['game_type']] }}
				@endif
				</div>
			</div>

			<div class="form-group">
				{{ Form::label('level', 'Level:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4">
					{{ Form::text('level', $game['level'], [ 'class' => 'form-control' ] ) }}
				</div>
			</div>
			
			<div class="form-group">
				{{ Form::label('name', 'Game name:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4">
					{{ Form::text('name', $game['name'], [ 'class' => 'form-control' ] ) }}
				</div>
			</div>
			
			<div class="form-group">
				{{ Form::label('instructions', 'Instructions:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				{{ Form::hidden('instructions', '') }}
				<div class="col-sm-6" contenteditable="true" id="instructionsInput">
				@if(is_null($game))
					'Enter instructions here...'
				@else
					{{ $game['instructions'] }}
				@endif
				</div>
			</div>

			<div class="form-group">
				{{ Form::label('extraInfo', 'Extras:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4" id="extraInfo">
				@if(is_null($game))
					<!-- This DIV should be filled depending on selected task type -->
				@else
					{{ $gameTypeDivs[$game->game_type] }}
				@endif
				</div>
			</div>
			
			<div class="form-group">
				{{ Form::label('tasks', 'Tasks:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-6">
					{{ Form::hidden('tasks', '') }}
					<ul class="list-group" id="taskList">
					</ul>
					
					{{ Form::label('newTask', 'Add task:', [ 'class' => 'col-sm-2 control-label' ] ) }}
					<div class="col-sm-8">
						{{ Form::text('newTask', '', [ 'class' => 'form-control' ] ) }}
					</div>
					
					<div class="col-sm-2">
						<a class="btn btn-success" onClick="addTask();"><i class="fa fa-search fa-fw"></i>+ Add new</a>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					{{ Form::submit('Save game', [ 'onClick' => 'doSubmit()' ]) }}
				</div>
			</div>
		{{ Form::close() }}
	</div>
@stop

@section('body-javascript')
	<script>
		$(document).ready(function(){

		@if(is_null($game))
			$("#game_type").change(function(){
				changeExtrasDiv($("#game_type").val());
			});
			$("#game_type").change();
		@endif
			
		// Load task list
		@foreach ($tasks as $task)
    		appendTask('{{ $task }}');
		@endforeach

		});
	</script>
@stop

