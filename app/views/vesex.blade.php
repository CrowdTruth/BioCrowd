@extends('layout')

@section('extraheaders')
<script type="text/javascript">
function formExtention(){
	if(questionForm.divided.checked == true){
		document.getElementById("hiddenQuestions").style.display = "none";
		//set the two checkboxes to unchecked again
		document.getElementById("tip").checked = false;
		document.getElementById("nucleus").checked = false;
	} else {
		document.getElementById("hiddenQuestions").style.display = "block";
	}
}
</script>
@stop

@section('content')
<div class='col-xs-9 maincolumn' style="background: none; width: 72%">
	<div class='row gameContent' style="width: 100%; height: 100%;">
		<section class="container" style="padding: 10px 10px; font-family: Verdana, Geneva, sans-serif; color: #333333; font-size: 0.9em;">
			<div class="row col-md-12" style="width: 76%;">
		<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->
		<!-- Bootstrap v3.0.3 -->
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
				<div class="row">
    				<div class="span7">

    				<?php $imageWidth = getimagesize($image)[0];
    				$imageHeight = getimagesize($image)[1];
    				$maxWidth = 810;
    				$maxHeight = 420;?>
    					@if(($imageWidth > $maxWidth) && ($imageHeight > $maxHeight)) 
    						@if($imageWidth >= $imageHeight)
      							<img id="annotatableImage" src="{{ $image }}" width="810px" />
      						@elseif($imageHeight >= $imageWidth)
      							<img id="annotatableImage" src="{{ $image }}" height="420px" />
      						@endif
      					@elseif($imageWidth > $maxWidth)
      						<img id="annotatableImage" src="{{ $image }}" width="810px" />
      					@elseif($imageHeight > $maxHeight)
      						<img id="annotatableImage" src="{{ $image }}" height="420px" />
      					@else
      						<img id="annotatableImage" src="{{ $image }}" />
      					@endif
      					
    				</div>
    			</div>
    			<BR>
    			<div class="span4">
      				{{ Form::open(array('url' => 'submitGame', 'name' => 'questionForm')) }}
      				{{ Form::hidden('gameId', $gameId) }}
      				{{ Form::hidden('taskId', $taskId) }}
      				{{ Form::checkbox('distributed', 'Yes', false , [ 'id' => 'divided' , 'onclick' => 'return formExtention();']) }}
      				{{ Form::label('distributed', 'The vesicles are equally distributed') }}
      				<div id="hiddenQuestions" style="display: block"> <!-- Hide these when the user clicked on the "equally distributed" box, because they don't add anything. If someone were to check them AND check the distributed one, it would NOT find spammers because if the vesicles are everywhere, they are naturally also near the nucleus and tips. -->
      				<div>
      					{{ Form::checkbox('tip', 'Yes', false , [ 'id' => 'tip' ]) }}
      					{{ Form::label('tip', 'The vesicles are near the tip') }}
      				</div>
      				<div>
      					{{ Form::checkbox('nucleus', 'Yes', false , [ 'id' => 'nucleus' ]) }}
      					{{ Form::label('nucleus', 'The vesicles are near the nucleus') }}
      				</div>
      				</div>
      				<div id="None of the above"> <!-- This statement can be used to check for spammers, so keep this open as an option when the rest is checked -->
      					{{ Form::checkbox('novesicles', 'true', false , [ 'id' => 'novesicles' ]) }}
      					{{ Form::label('novesicles', 'There are no vesicles in this image') }}
      				</div>
      				
      				<p>
      				
      				<table width="100%">
     		 		<tr><td align="center">{{ Form::submit('Submit') }}</td></tr>
      				</table>
      				{{ Form::close() }}
      			</div>
    		</div>


		<!-- close container -->


		<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->
	</section>
	</div>
</div>

@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop