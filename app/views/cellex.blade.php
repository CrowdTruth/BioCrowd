@extends('layout')

@section('extraheaders')
	<script src="js_final/ct-annotate.js"></script>
	<script>
		/**
		 * Update annotation count and enable/disable submit button accordingly -- at least
		 * one annotation must be made (or the no-annotation check box must be clicked).
		 */
		function updateAnnotationCount() {
			var count = ct_annotate.getAnnotations().length;
			$('#nrTags').html(count);
			
			if((annotationForm.noCells.checked == false) && (count == 0)) {
				//If the "noCells" checkbox is unchecked and there are no annotations, disable the submit button
				document.getElementById("disabledSubmitButton").disabled = true;
			} else {
				document.getElementById("disabledSubmitButton").disabled = false;
			}
		}

		/**
		 * Prepare response to be submitted.
		 */
		function prepareResponse() {
			response = ct_annotate.getAnnotations();
			response = JSON.stringify(response);
			$('#response').val(response);
		}
	</script>
@stop

@section('content')
<div class='col-xs-9 maincolumn' style="background: none; width: 72%">
	<div class='row gameContent' style="width: 100%; height: 100%;">
		<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->
		<section class="container" style="padding: 10px 10px; font-family: Verdana, Geneva, sans-serif; color: #333333; font-size: 0.9em;">
			<div class="row col-md-12" style="width: 76%;">
				<!-- Instructions -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<strong>Instructions</strong>
					</div>

					<div class="panel-body">
						{{ $instructions }}
					</div>
				</div>
				<!-- End Instructions -->
				
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="span7">
							<canvas id="annotationCanvas"></canvas>
						</div>
						<div class="span7">
							{{ Form::button('Remove last', ['onClick' => 'ct_annotate.removeLast()']) }}							
						</div>
						
						<div class="span4">
							<p>
							<div class="cml_field">
								<div class="border">
									Number of bounding boxes: <span class="nrTags" id="nrTags">0</span>
								</div>
							</div>
							<br>
							{{ Form::open([ 'url' => 'submitGame', 'name' => 'annotationForm' ]) }}
							{{ Form::hidden('gameId', $gameId) }}
							{{ Form::hidden('taskId', $taskId) }}
							{{ Form::hidden('response','', [ 'id' => 'response' ] ) }}
							<div id="None of the above"> <!-- This statement can be used to check for spammers, so keep this open as an option when the rest is checked -->
								{{ Form::checkbox('noCells', 'true', false , [ 'id' => 'noCells', 'onClick' => 'updateAnnotationCount();' ]) }}
								{{ Form::label('noCells', $resposeLabel) }}
							</div>
							<table width="100%">
								<tr><td align="center">{{ Form::submit('Submit', ['id' => 'disabledSubmitButton', 'onClick' => 'prepareResponse();' ]) }}</td></tr>
							</table>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->
		</section>
	</div>
</div>
<script>
	$(document).ready(function(){
		document.getElementById("disabledSubmitButton").disabled = true;

		// Prepare canvas for annotation using ct_annotate library
		canvas = document.getElementById('annotationCanvas');

		// Trigger our function when annotation takes place.
		canvas.addEventListener('annotationChanged', updateAnnotationCount, false);

		// Perhaps doRect, styleDrag, styleFixed should be loaded from DB ?
		doRect=false;		// Draw ellipses
		styleDrag='red';	// Use red lines while drawing
		styleFixed='yellow';// Use yellow lines for established annotations
		ct_annotate.loadCanvasImage(canvas, '{{ $image }}', doRect, styleDrag, styleFixed);
	});
</script>
@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop
