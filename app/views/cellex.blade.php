@extends('layout')

@section('extraheaders')
	<script src="js_final/ct-annotate.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
						<form>
							<input type="hidden" name="totaltime">
							<input type="hidden" name="tagtiming">
							<input type="hidden" name="starttime">
							<input type="hidden" name="endtime">
							<input type="hidden" name="annotations">
							<input type="hidden" name="fullsizeimageclicked" value="false">
							<input type="hidden" name="contributors_browser" validates="user_agent">
						</form>
						<div class="span7">
							<canvas id="annotationCanvas"></canvas>
						</div>
						
						<div class="span4">
							<p>
							<div class="cml_field">
								<div class="border">
									Number of bounding boxes: <span class="nrTags" id="nrTags">0</span>
								</div>
							</div>
							<br>

							{{ Form::open(array('url' => 'submitGame', 'name' => 'annotationForm')) }}
							{{ Form::hidden('gameId', $gameId) }}
							{{ Form::hidden('taskId', $taskId) }}
							{{ Form::hidden('response','', [ 'id' => 'response' ] ) }}
							<div id="None of the above"> <!-- This statement can be used to check for spammers, so keep this open as an option when the rest is checked -->
								{{ Form::checkbox('noCells', 'true', false , [ 'id' => 'noCells', 'onclick' => 'return taggingFormExtention();' ]) }}
								{{ Form::label('noCells', $resposeLabel) }}
							</div>
							<table width="100%">
								<tr><td align="center">{{ Form::submit('Submit', ['id' => 'disabledSubmitButton']) }}</td></tr>
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
		doRect = true;
		
		canvas = document.getElementById('annotationCanvas');
		ct_annotate.loadCanvasImage(canvas, '{{ $image }}', doRect);
	});
</script>
@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop
