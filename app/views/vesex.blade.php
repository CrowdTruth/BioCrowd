@extends('baseGameView')

@section('extraheaders')
<link href="/css/vesEx.css" rel="stylesheet">
<script type="text/javascript">
function formExtention(){
	if(annotationForm.distributed.checked == true){
		document.getElementById("hiddenQuestions").style.display = "none";
		//set the two checkboxes to unchecked again
		document.getElementById("tip").checked = false;
		document.getElementById("nucleus").checked = false;
	} else {
		document.getElementById("hiddenQuestions").style.display = "block";
	}
}

function setFormItem(value){
	//set the value of the hidden form item "location"
	document.getElementById("location").value = value;

	//set the submitbutton to enabled
	document.getElementById("disabledSubmitButtonVesEx").disabled = false;

	//set the border color of all children to white
	var childrenArray = document.getElementById("vesicleClassificationButtonsList").children;
	for(var	i=0; i<childrenArray.length; i++){
		childrenArray[i].style.border = "1px solid #fff";
	}

	//set the border of the clicked item to blue
	document.getElementById(value).style.border = "1px solid #369";
}


/**
* Expand the TextArea for "Other" in the annotationform
*/
function expandOtherTextArea() {
	if(annotationForm.other.checked == false){
		document.getElementById("hiddenOtherExpand").style.display = "none";
	} else {
		document.getElementById("hiddenOtherExpand").style.display = "block";
	}
}

/**
* Show the TextArea for "Comment" in the annotationForm
*/
function showCommentForm() {
	if(annotationForm.commentFormPlease.checked == false){
		document.getElementById("hiddenCommentForm").style.display = "none";
	} else {
		document.getElementById("hiddenCommentForm").style.display = "block";
	}
}

$(document).ready(function(){
	document.getElementById("disabledSubmitButtonVesEx").disabled = true;
});

</script>
@stop

@section('gameForm')

<label>step 1: {{$responseLabel[0]}}</label><BR/>
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
	<div class="stepOne">
		<br/>
		<div class="span4" style='font-size:18pt;'>Classify this picture</div>
		<div class='vesicleClassificationButtons'>
			<ul id='vesicleClassificationButtonsList'> <!-- TO DO: make the images vertical align bottom, so the BRs are no longer necessary -->
				<li id='clustered'><BR>Clustered<BR/><img width='100%' style='bottom: 5px; padding:5px' src='img/VesExButtonIcons/VesClusterIcon.jpg' onmousedown='setFormItem("clustered")'></li>
				<li id='tip'><BR>Tip<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/VesTipIcon.jpg' onmousedown='setFormItem("tip")'></li>
				<li id='fog'><BR>Fog<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/VesFogIcon.jpg' onmousedown='setFormItem("fog")'></li>
				<li id='sideNucleus'><BR>{{$responseLabel[1]}}<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/VesSideNucleusIcon.jpg' onmousedown='setFormItem("sideNucleus")'></li>
				<li id='ringAroundNucleus'>{{$responseLabel[2]}}<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/VesRingIcon.jpg' onmousedown='setFormItem("ringAroundNucleus")'></li>
				<li id='black'><BR>Black<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/Black.jpg' onmousedown='setFormItem("black")'></li>
				<li id='noneOfThese'>None of these<BR/><img id='noneOfThese' width='100%' style='padding:5px' src='img/VesExButtonIcons/NoneOfThese.jpg' onmousedown='setFormItem("noneOfThese")'></li>
				<li id='noImage'><BR>No image<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/NoImage.jpg' onmousedown='setFormItem("noImage")'></li>
				<li id='dontKnow'><BR>I don't know<BR/><img width='100%' style='padding:5px' src='img/VesExButtonIcons/DontKnow.jpg' onmousedown='setFormItem("dontKnow")'></li>
			</ul>
	    </div>
		{{ Form::hidden('location', '', ['id' => 'location']) }}
	</div>
	
	<div class="stepTwo">			
		<label style='padding-top: 20px;'>step 2</label>
		<BR/>
		<div>Please select all which apply to your selection from STEP 1.</div>
		{{ Form::radio('markingDescription', 'allVesicles', false, ['onClick' => 'expandOtherTextArea();', 'required'=>'required' ]) }}
		{{ Form::label('markingDescription', $responseLabel[3]) }}<BR/>
		{{ Form::radio('markingDescription', 'allVesicles', false, ['onClick' => 'expandOtherTextArea();', 'required'=>'required' ]) }}
		{{ Form::label('markingDescription', $responseLabel[4], false, ['required'=>'required' ]) }}<BR/>
		{{ Form::radio('markingDescription', 'noCell', false, ['onClick' => 'expandOtherTextArea();', 'required'=>'required' ]) }}
		{{ Form::label('markingDescription', $responseLabel[5]) }}<BR/>
		{{ Form::radio('markingDescription', 'other', false, [ 'id' => 'other', 'onClick' => 'expandOtherTextArea();', 'required'=>'required' ]) }}
		{{ Form::label('markingDescription', 'Other') }}<BR/>
		<div id="hiddenOtherExpand" style="display: none">
			<BR/>
			{{ Form::label('otherExpand', 'Please expand on your choice of OTHER') }}<BR/>
			{{ Form::textarea('otherExpand') }}
		</div>
		<BR/>
	</div>
	<div class="stepThree">
		<label>step 3: Which BEST describes the IMAGE QUALITY</label>
		<div>Image Sharpness</div>
		{{ Form::radio('qualityDescription', 'good', false, ['required'=>'required' ]) }}
		{{ Form::label('qualityDescription', 'Good') }} <BR/>
		{{ Form::radio('qualityDescription', 'medium', false, ['required'=>'required' ]) }}
		{{ Form::label('qualityDescription', 'Medium') }} <BR/>
		{{ Form::radio('qualityDescription', 'poor', false, ['required'=>'required' ]) }}
		{{ Form::label('qualityDescription', 'Poor') }} <BR/>
		{{ Form::radio('qualityDescription', 'blank', false, ['required'=>'required' ]) }}
		{{ Form::label('qualityDescription', 'Blank (Black) Image') }}<BR/>
		{{ Form::radio('qualityDescription', 'noImage', false, ['required'=>'required' ]) }}
		{{ Form::label('qualityDescription', 'No Image') }}
		<BR/>
		<BR/>
	</div>
	<div class="optional">
		<label>OPTIONAL</label>
		<div>Would you like to make any comments on this image?</div>
		{{ Form::radio('comments', 'yesComments', false, ['id' => 'commentFormPlease', 'onClick' => 'showCommentForm();', 'required'=>'required' ]) }}
		{{ Form::label('comments', 'Yes') }} <BR/>
		{{ Form::radio('comments', 'noComments', false, ['id' => 'noCommentFormPlease', 'onClick' => 'showCommentForm();', 'required'=>'required' ]) }}
		{{ Form::label('comments', 'No') }} <BR/>
		<div id="hiddenCommentForm" style="display: none">
			<BR/>
			{{ Form::label('comment', 'Thank you for providing relevant information. Please make your comments here:') }}<BR/>
			{{ Form::textarea('comment') }}
		</div>
	</div>
			
	<table width="100%">
		<tr><td align="center">{{ Form::submit('Submit', ['id' => 'disabledSubmitButtonVesEx']) }}</td></tr>
	</table>
</div>

@stop
