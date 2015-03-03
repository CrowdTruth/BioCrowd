@extends('baseGameView')

@section('extraheaders')
<script type="text/javascript">
function formExtention(){
	if(questionForm.distributed.checked == true){
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

@section('gameForm')


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
    			<BR>
    			<div class="span4">
      				{{ Form::hidden('gameId', $gameId) }}
      				{{ Form::hidden('taskId', $taskId) }}
      				{{ Form::checkbox('distributed', 'Yes', false , [ 'id' => 'distributed' , 'onclick' => 'return formExtention();']) }}
      				{{ Form::label('distributed', $label1) }}
      				<div id="hiddenQuestions" style="display: block"> <!-- Hide these when the user clicked on the "equally distributed" box, because they don't add anything. If someone were to check them AND check the distributed one, it would NOT find spammers because if the vesicles are everywhere, they are naturally also near the nucleus and tips. -->
      				<div>
      					{{ Form::checkbox('tip', 'Yes', false , [ 'id' => 'tip' ]) }}
      					{{ Form::label('tip', $label2) }}
      				</div>
      				<div>
      					{{ Form::checkbox('nucleus', 'Yes', false , [ 'id' => 'nucleus' ]) }}
      					{{ Form::label('nucleus', $label3) }}
      				</div>
      				</div>
      				<div id="None of the above"> <!-- This statement can be used to check for spammers, so keep this open as an option when the rest is checked -->
      					{{ Form::checkbox('novesicles', 'true', false , [ 'id' => 'novesicles' ]) }}
      					{{ Form::label('novesicles', $responseLabel) }}
      				</div>
      				
      				<p>
      				
      				<table width="100%">
     		 		<tr><td align="center">{{ Form::submit('Submit') }}</td></tr>
      				</table>
      			</div>

@stop
