@extends('baseGameView')

@section('extraheaders')
	<script src="js/ct-annotate.js"></script>
	<script>
		/**
		 * Update annotation count and enable/disable submit button accordingly -- at least
		 * one annotation must be made (or the no-annotation check box must be clicked).
		 */
		function updateAnnotationCount() {
			var count = ct_annotate.getAnnotations().length;
			$('#nrTags').html(count);
			
			if((annotationForm.noCells.checked == false) && 
				(annotationForm.noImage.checked == false) && 
				(annotationForm.blankImage.checked == false) &&
				(annotationForm.other.checked == false) &&
				(count == 0)) {
				//If the "noCells" checkbox is unchecked and there are no annotations, disable the submit button
				document.getElementById("disabledSubmitButton").disabled = true;
			} else {
				document.getElementById("disabledSubmitButton").disabled = false;
			}
		}

		function expandOtherTextArea() {
			if(annotationForm.other.checked == false){
				document.getElementById("hiddenOtherExpand").style.display = "none";
			} else {
				document.getElementById("hiddenOtherExpand").style.display = "block";
			}
		}

		function showCommentForm() {
			if(annotationForm.commentFormPlease.checked == false){
				document.getElementById("hiddenCommentForm").style.display = "none";
			} else {
				document.getElementById("hiddenCommentForm").style.display = "block";
			}
		}

		function updateShapeSelection(shape) {
			if(shape == 'rectangle') {
				ct_annotate.doRectangle(true);
			} else {
				ct_annotate.doRectangle(false);
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

			document.getElementById("useRectangle").checked = doRect;
			document.getElementById("useEllipse").checked = !doRect;
			ct_annotate.loadCanvasImage(canvas, '{{ $image }}', doRect, styleDrag, styleFixed);
		});
	</script>
@stop

@section('gameForm')
	<div class="span7">
		<label>step 1: {{$responseLabel[0]}} </label>
		<table>
		<tr><td>
			<canvas id="annotationCanvas"></canvas>
			</td>	
			<td width="20px"></td>
		<td>
			{{ Form::radio('shape', 'Rectangle', false, [ 'id' => 'useRectangle', 'onClick' => 'updateShapeSelection("rectangle")' ]) }} Rectangle <br/>
			{{ Form::radio('shape', 'Ellipse'  , true , [ 'id' => 'useEllipse', 'onClick' => 'updateShapeSelection("ellipse")' ]) }} Ellipse
		</td>
		</table>
	</div>
	<div class="span7">
		{{ Form::button('Remove last', ['onClick' => 'ct_annotate.removeLast()']) }}
	</div>

	<div class="span4">
		<p>
		<div class="cml_field">
			<!-- div class="border">
				Number of bounding boxes: <span class="nrTags" id="nrTags">0</span>
			</div -->
		</div>
		<br>
		{{ Form::hidden('gameId', $gameId) }}
		{{ Form::hidden('taskId', $taskId) }}
		{{ Form::hidden('response','', [ 'id' => 'response' ] ) }}
		<div id="markingDescription">
		<label>step 2</label><BR/>
			{{ Form::radio('markingDescription', 'allCells', false , [ 'id' => 'allCells', 'onClick' => 'updateAnnotationCount();expandOtherTextArea();', 'required'=>'required' ] ) }}
			{{ Form::label('markingDescription', $responseLabel[1]) }} <BR/>
			{{ Form::radio('markingDescription', 'tooManyCells', false , [ 'id' => 'tooManyCells', 'onClick' => 'updateAnnotationCount();expandOtherTextArea();', 'required'=>'required' ]) }}
			{{ Form::label('markingDescription', $responseLabel[2]) }} <BR/>
			{{ Form::radio('markingDescription', 'noCells', false , [ 'id' => 'noCells', 'onClick' => 'updateAnnotationCount();expandOtherTextArea();', 'required'=>'required' ]) }}
			{{ Form::label('markingDescription', $responseLabel[3]) }} <BR/>
			{{ Form::radio('markingDescription', 'other', false , [ 'id' => 'other', 'onClick' => 'updateAnnotationCount();expandOtherTextArea();', 'required'=>'required' ]) }}
			{{ Form::label('markingDescription', $responseLabel[4]) }}<BR/>
			<div id="hiddenOtherExpand" style="display: none">
			<BR/>
			{{ Form::label('otherExpand', 'Please expand on your choice of OTHER') }}<BR/>
			{{ Form::textarea('otherExpand') }}</div>
			</div>
		<BR/>
		<label>step 3</label>
		<div>{{$responseLabel[5]}}</div>
			{{Form::text('totalCells','',['required'])}}
		<BR/>
		<BR/>
		<label>step 4: what best describes the image quality</label>
		<div>Image Sharpness</div>
			{{ Form::radio('qualityDescription', 'good', false, [ 'id' => 'goodQuality', 'onClick' => 'updateAnnotationCount();', 'required'=>'required' ]) }}
			{{ Form::label('qualityDescription', $responseLabel[6]) }} <BR/>
			{{ Form::radio('qualityDescription', 'medium', false, [ 'id' => 'mediumQuality', 'onClick' => 'updateAnnotationCount();', 'required'=>'required' ]) }}
			{{ Form::label('qualityDescription', $responseLabel[7]) }} <BR/>
			{{ Form::radio('qualityDescription', 'poor', false, [ 'id' => 'poorQuality', 'onClick' => 'updateAnnotationCount();', 'required'=>'required' ]) }}
			{{ Form::label('qualityDescription', $responseLabel[8]) }} <BR/>
			{{ Form::radio('qualityDescription', 'blank', false, [ 'id' => 'blankImage', 'onClick' => 'updateAnnotationCount();', 'required'=>'required' ]) }}
			{{ Form::label('qualityDescription', $responseLabel[9]) }}<BR/>
			{{ Form::radio('qualityDescription', 'noImage', false, [ 'id' => 'noImage', 'onClick' => 'updateAnnotationCount();', 'required'=>'required' ]) }}
			{{ Form::label('qualityDescription', $responseLabel[10]) }}
		<BR/>
		<BR/>
		<label>OPTIONAL</label>
		<div>Would you like to make any comments on this image?</div>
			{{ Form::radio('comments', 'yesComments', false, ['id' => 'commentFormPlease', 'onClick' => 'showCommentForm();', 'required'=>'required' ]) }}
			{{ Form::label('comments', 'Yes') }} <BR/>
			{{ Form::radio('comments', 'noComments', false, ['id' => 'noCommentFormPlease', 'onClick' => 'showCommentForm();', 'required'=>'required' ]) }}
			{{ Form::label('comments', 'No') }} <BR/>
			<div id="hiddenCommentForm" style="display: none">
			<BR/>
			{{ Form::label('comment', 'Thank you for providing relevant information. Please make your comments here:') }}<BR/>
			{{ Form::textarea('comment') }}</div>
			</div>
			
		<table width="100%">
			<tr><td align="center">{{ Form::submit('Submit', ['id' => 'disabledSubmitButton', 'onClick' => 'prepareResponse();' ]) }}</td></tr>
		</table>
	</div>
@stop
