@extends('baseGameView')

@section('extraheaders')
<link href="css/vesEx.css" rel="stylesheet">
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

	//only send the location to the database if it was changed. 
	if(locationWasChanged){
		var input = $('#location').attr('value');
		var attribute = 'location';
		updateDB(attribute, input);
	}

	//set the background color of all children to white and the text color to #333
	var childrenArray = document.getElementById("vesicleClassificationButtonsList").children;
	for(var	i=0; i<childrenArray.length; i++){
		childrenArray[i].style.backgroundColor = "#17ad4b";
		childrenArray[i].style.color = "#333";
	}

	//set the background color of the clicked item to blue and its text to white
	document.getElementById(value).style.backgroundColor = "#369";
	document.getElementById(value).style.color = "white";
}

/**
 * Update annotation count and enable/disable submit button accordingly -- at least
 * one annotation must be made (or the no-annotation check box must be clicked).
 */
function updateDisabledSubmitButtonVesEx() {
	if((annotationForm.noCells.checked == false) && 
		(annotationForm.noImage.checked == false) && 
		(annotationForm.blankImage.checked == false) &&
		(annotationForm.other.checked == false) &&
		(document.getElementById("location").value == "")) {
		//If the "noCells" checkbox, the "noImgae" checkbox, the "blankImage" checkbox and the "other" checkbox are unchecked, disable the submit button
		document.getElementById("disabledSubmitButtonVesEx").disabled = true;
		return false;
	} else {
		document.getElementById("disabledSubmitButtonVesEx").disabled = false;
		return false;
	}
}

/**
* Expand the TextArea for "Other" in the annotationform
*/
function expandOtherTextArea() {
	if(annotationForm.other.checked == false){
		document.getElementById("hiddenOtherExpand").style.display = "none";
		document.getElementById('otherExpand').value = "";
		otherExpandWasChanged = true;
		var input = $("#otherExpand").val();
		var attribute = 'otherExpand';
		updateDB(attribute, input);
	} else {
		document.getElementById("hiddenOtherExpand").style.display = "block";
	}
}

$(document).ready(function(){
	document.getElementById("disabledSubmitButtonVesEx").disabled = true;
});

window.onload = function() {
	//when loading, tell the css what the location value is and calculate the progress percentage. 
	//If the page reloads in Firefox this is needed. 
	setFormItem(document.getElementById("location").value);
	calculateProgressPercentage();
};

</script>

	<script type="text/javascript">
		//On window size < 480, set the ribbon logo div behind the ribbon button div, so it borders on the lower section properly.
		$(window).resize(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribimg").detach();
					$(detach).insertAfter($(this).find("#ribbutton"));
				})
			} else if (width > 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribbutton").detach();
					$(detach).insertAfter($(this).find("#ribimg"));
				})
			}
		});
	
		//Reset logo div in front of button on larger size.
		$(document).ready(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribimg").detach();
					$(detach).insertAfter($(this).find("#ribbutton"));
				})
			} else if (width > 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribbutton").detach();
					$(detach).insertAfter($(this).find("#ribimg"));
				})
			}
		});
	</script>
	
	<script>
	//hides all the question divs, and shows the currently active one 
	//hides the buttons for back to marking and back to previous question
	//hides the completed game screen div
	$(document).ready(function() {
		$('.question').hide();
		$('.question_active').show();
		$('.goPreviousQuestion').css('visibility','hidden');
		$('.goFinish').hide();
		$('#completed_game_container').hide();
		$('#completed_game_popup').hide();
		$('.examplePopup').hide();
		
		$(".markingDescription").change(function() {
		    var input = $('.markingDescription:checked').attr('value');
		    var attribute = 'markingDescription';
		    updateDB(attribute, input);
		});

		$("#otherExpand").keyup(function() {
			otherExpandWasChanged = true;
		    var input = $(this).val();
		    var attribute = 'otherExpand';
		    updateDB(attribute, input);
		});

		$(".qualityDescription").change(function() {
		    var input = $('.qualityDescription:checked').attr('value');
		    var attribute = 'qualityDescription';
		    updateDB(attribute, input);
		});

		$("#comment").keyup(function() {
			commentWasChanged = true;
		    var input = $(this).val();
		    var attribute = 'comment';
		    updateDB(attribute, input);
		});

		var consecutiveGame = "<?php echo Session::get('consecutiveGame');?>";
		var flag = "<?php echo Session::get('flag');?>";
		
		if(consecutiveGame == "consecutiveGame"){
			$('#dropdown_container').hide();
			$('#completed_game_popup').show();
		} else {
			$('#completed_game_popup').hide();
			//Make a new judgement with flag incomplete to start the timer
			otherExpandWasChanged = false;
			commentWasChanged = false;
			updateDB(null, null);
		}

		$( window ).load(function() {
			if(flag == "skipped"){	    	
		    	$('#dropdown_container').slideDown(500);
		    	$('.startgame').hide();
				$('#ribbon').css({
					"height" : "200px",
				});
				$('html, body').animate({
	                scrollTop: $("#ribbon").height()+$("#banner").height()
	            },2000);
				if($window.width() > 508){
					$('#logic_container').height($('#game_container').height());
				}
			}
		});

		//add function to open and close the examples popup
		$('.openExamples').on({
			'click' : function() {
				if ($('.examplePopup').is(":visible")) {
					$('.examplePopup').hide();
					$('.openExamples').text('Show detailed instructions');
				} else {
					$('.examplePopup').show();
					$('.openExamples').text('Hide detailed instructions');
				}
			}
		});

		//add function to next question button to hide current question div and show next question div
		$('.goNextQuestion').on({
			'click' : function() {
				$('.question_active').hide().removeClass(function() {

					if ($(this).is("#question3")) {
						$(this).next().addClass('question_active').show();
						$('.goNextQuestion').css('visibility', 'hidden');
						$('.goFinish').show();
						return 'question_active';
					} else {
						$(this).next().addClass('question_active').show();
						$('.goPreviousQuestion').css('visibility','visible');
						return 'question_active';
					}
				})
			}
		})

		//add function to previous question button to hide current question div and show previous question div
		$('.goPreviousQuestion').on({
			'click' : function() {
				$('.question_active').hide().removeClass(function() {
					if ($(this).is("#question2")) {
						$('#question1').addClass('question_active').show();
						$('.goPreviousQuestion').css('visibility','hidden');
						return 'question_active';
					} else if ($(this).is("#question4")) {
						$('#question3').addClass('question_active').show();
						$('.goNextQuestion').css('visibility', 'visible');
						$('.goFinish').hide();
						return 'question_active';
					} else {
						if ($(this).prev().hasClass('question')) {
							$(this).prev().addClass('question_active').show();
							return 'question_active';
						}
					}
				})
			}
		})
		
		//add function to remove the game recap and start the game again	
		$('.goPlayAgain').on({
			'click' : function() {	
				$('#question1').addClass('question_active').show();
				$('#dropdown_container').show();
				$('#completed_game_container').hide();
				$('#completed_game_popup').hide();	
				//Make a new judgement with flag incomplete to start the timer
				otherExpandWasChanged = false;
				commentWasChanged = false;
				updateDB(null, null);
			}
		});
	})
</script>
	
	<script>
		function calculateProgressPercentage(){
			var totalAmountOfQuestions = 4;
			var calculateAnsweredFormItems = this.calculateAnsweredFormItems();
			document.getElementById("vesExProgressBar").innerHTML = Math.round(((calculateAnsweredFormItems.length/totalAmountOfQuestions)*100))+'\%';
			document.getElementById("vesExProgressBar").style.width = ((calculateAnsweredFormItems.length/totalAmountOfQuestions)*100)+'\%';
		}
		
		function calculateAnsweredFormItems() {
			var calculatedAnswerArray = new Array();
			var test = document.getElementById("location").value;
			if(document.getElementById("location").value != '' || document.getElementById("noCells").checked){
				calculatedAnswerArray.push(1);
			}

			var markingDescriptionCheckBoxes = document.getElementsByClassName('markingDescription');

			for (var i = 0; i < markingDescriptionCheckBoxes.length; i++) {
				if(markingDescriptionCheckBoxes[i].checked){
					calculatedAnswerArray.push(2);
				}        
			}

			var qualityCheckBoxes = document.getElementsByClassName('qualityDescription');

			for (var i = 0; i < qualityCheckBoxes.length; i++) {
				if(qualityCheckBoxes[i].checked){
					calculatedAnswerArray.push(3);
				}        
			}

			if(document.getElementById("comment").value != ""){
				calculatedAnswerArray.push(4);
			}
			
			return calculatedAnswerArray;
		}
	</script>
	
	<script>
		//hide current question div and show first required empty question form div
		function putUnansweredQuestionOnTop() {
			var totalAmountOfQuestions = 5;
			var calculateAnsweredFormItems = this.calculateAnsweredFormItems();
			for(var i = 2; i <= totalAmountOfQuestions; i++) {
				var itsInThere = calculateAnsweredFormItems.indexOf(i);
				if (itsInThere == -1){
					$('.question_active').hide().removeClass("question_active");
					$('#question'+i).addClass('question_active').show();
					if(i != totalAmountOfQuestions) {
						$('.goNextQuestion').css('visibility', 'visible');
						$('.goFinish').hide();
					}
					return;
				}
			}
		}
	</script>
	
	<script>
		function showDrawnList1(){
			$('.ct_object_list').css("cssText", "display: inline-block !important;");
			$('#showDrawnList').css("cssText", "display: none !important;");
			$('#hideDrawnList').css("cssText", "display: inline-block !important;");
		}
	
		function hideDrawnList1(){
			$('.ct_object_list').css("cssText", "display: none !important;");
			$('#showDrawnList').css("cssText", "display: inline-block !important;");
			$('#hideDrawnList').css("cssText", "display: none !important;");
		}
	</script>
	
	<script>
	function makeQuestionsNonRequired(){
		var markingDescriptionElements = document.getElementsByClassName("markingDescription");
		for(var i = 0; i < markingDescriptionElements.length; i++){
			markingDescriptionElements[i].required = false;
		}
		
		var qualityDescriptionElements = document.getElementsByClassName("qualityDescription");
		for(var i = 0; i < qualityDescriptionElements.length; i++){
			qualityDescriptionElements[i].required = false;
		}
	}
	</script>
	
	<script>
	function flagThisTask(){
		document.getElementById("flag").value="skipped";
	}
	</script>
	
	<script>
	function updateDB(attribute, input){
		var gameId = "<?php echo $gameId?>";
		var taskId = "<?php echo $taskId?>";
		var campaignIdArray = "<?php if(isset($campaignIdArray)) { echo implode(",", $campaignIdArray); } ?>";
		$.ajax({   
			type: 'POST', 
			url: 'submitGame', 
			data: 'flag=incomplete&gameId='+gameId+'&taskId='+taskId+'&campaignIdArray='+campaignIdArray+'&'+attribute+'='+input+'&otherExpandWasChanged='+otherExpandWasChanged+'&commentWasChanged='+commentWasChanged
		});
	}
	</script>
	
	<script>
	$(document).ready(function() {
		locationWasChanged = false;
		otherExpandWasChanged = false;
		commentWasChanged = false;
		//set the variables of the radio boxes
		var markingDescription = "<?php echo $markingDescription;?>";
		if(document.getElementById(markingDescription)){
			document.getElementById(markingDescription).checked = true;
		}
		if(annotationForm.other.checked == true){
			document.getElementById("hiddenOtherExpand").style.display = "block";
		}
		var qualityDescription = "<?php echo $qualityDescription;?>";
		if(document.getElementById(qualityDescription)){
			document.getElementById(qualityDescription).checked = true;
		}
	});
	</script>
	
	<script>
	function setLocationWasChangedToTrue(){
		locationWasChanged = true;
	}
	</script>
	
@stop

@section('gameForm')
@if (Session::has('consecutiveGame'))
		<?php Session::get('consecutiveGame') ?>
@endif
<div class="section group" id="dropdown_container">
	<div class="col span_8_of_8">
		<div class="section group" id="progress_container">
			<div class="col span_8_of_8">
				<div id="game_progress">
					<div class="bar" id="vesExProgressBar" style="width: 0%;">0%</div>						
				</div>
			</div>		
		</div>
		<div class="section group" id="game_container">
			<div style="margin-left:31px"><H1>{{$gameName}}</H1></div>
			<div class="col span_5_of_8" id="question_container">
				<div id="question1" class="question question_active" style="display:inline-block;">
					<div class="textblock">
						<H1>Step 1: <img src="img/glyphs/image_questionmark-02.png" width="30px" title="{{$responseLabel[6]}}"></H1>
						<div class="gameInstructionText">{{$responseLabel[0]}}</div>
					</div>
					<BR>
					<div class='vesicleClassificationButtons'>
						<ul id='vesicleClassificationButtonsList'> <!-- TO DO: make the images vertical align bottom, so the BRs are no longer necessary -->
							<li width='88px' id='clustered' onmousedown='setLocationWasChangedToTrue(), setFormItem("clustered"), calculateProgressPercentage()'>Clustered<BR/><img id="clustered img" style='bottom: 5px; padding:5px' src='img/VesExButtonIcons/VesClusterIcon.jpg'></li>
							<li width='88px' id='tip' onmousedown='setLocationWasChangedToTrue(), setFormItem("tip"), calculateProgressPercentage()'>Tip<BR/><img id="tip img" style='padding:5px' src='img/VesExButtonIcons/VesTipIcon.jpg'></li>
							<li width='88px' id='fog' onmousedown='setLocationWasChangedToTrue(), setFormItem("fog"), calculateProgressPercentage()'>Fog<BR/><img id="fog img" style='padding:5px' src='img/VesExButtonIcons/VesFogIcon.jpg'></li>
							<li width='88px' id='sideNucleus' onmousedown='setLocationWasChangedToTrue(), setFormItem("sideNucleus"), calculateProgressPercentage()'>{{$responseLabel[1]}}<BR/><img id="sideNucleus img" style='padding:5px' src='img/VesExButtonIcons/VesSideNucleusIcon.jpg'></li>
							<li width='88px' id='ringAroundNucleus' onmousedown='setLocationWasChangedToTrue(), setFormItem("ringAroundNucleus"), calculateProgressPercentage()'>{{$responseLabel[2]}}<BR/><img id="ringAroundNucleus img" style='padding:5px' src='img/VesExButtonIcons/VesRingIcon.jpg'></li>
							<li width='88px' id='black' onmousedown='setLocationWasChangedToTrue(), setFormItem("black"), calculateProgressPercentage()'>Black<BR/><img id="black img" style='padding:5px' src='img/VesExButtonIcons/Black.jpg'></li>
							<li width='88px' id='noneOfThese' onmousedown='setLocationWasChangedToTrue(), setFormItem("noneOfThese"), calculateProgressPercentage()'>None of these<BR/><img id="noneOfThese img" style='padding:5px' src='img/VesExButtonIcons/NoneOfThese.jpg'></li>
							<li width='88px' id='noImage' onmousedown='setLocationWasChangedToTrue(), setFormItem("noImage"), calculateProgressPercentage()'>No image<BR/><img id="noImage img" style='padding:5px' src='img/VesExButtonIcons/NoImage.jpg'></li>
							<li width='88px' id='dontKnow' onmousedown='setLocationWasChangedToTrue(), setFormItem("dontKnow"), calculateProgressPercentage()'>I don't know<BR/><img id="dontKnow img" style='padding:5px' src='img/VesExButtonIcons/DontKnow.jpg'></li>
						</ul>
				    </div>
					<div style="display:none;">
						{{ Form::hidden('gameId', $gameId) }}
						{{ Form::hidden('taskId', $taskId) }}
						{{ Form::hidden('location', $location, ['id' => 'location']) }}
						{{ Form::hidden('flag', '', [ 'id' => 'flag' ] ) }}
					</div>
				</div>
				<div id="question2" class="question" >
					<div class="textblock">
						<H1>Step 2: <img src="img/glyphs/image_questionmark-02.png" width="30px" title="{{$responseLabel[7]}}"></H1>
						<div class="gameInstructionText">Please select all which apply to your selection from step 1.</div>
						{{ Form::radio('markingDescription', 'allVesicles', false, ['id' => 'allVesicles', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), updateDisabledSubmitButtonVesEx(), calculateProgressPercentage();', 'required'=>'required' ]) }}
						{{ Form::label('allVesicles', $responseLabel[3]) }}<BR/>
						{{ Form::radio('markingDescription', 'mixed', false, ['id' => 'mixed', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), updateDisabledSubmitButtonVesEx(), calculateProgressPercentage();', 'required'=>'required' ]) }}
						{{ Form::label('mixed', $responseLabel[4]) }}<BR/>
						{{ Form::radio('markingDescription', 'noCells', false, ['id' => 'noCells', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), updateDisabledSubmitButtonVesEx(), calculateProgressPercentage();', 'required'=>'required' ]) }}
						{{ Form::label('noCells', $responseLabel[5]) }}<BR/>
						{{ Form::radio('markingDescription', 'other', false, [ 'id' => 'other', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), updateDisabledSubmitButtonVesEx(), calculateProgressPercentage();', 'required'=>'required' ]) }}
						{{ Form::label('other', 'Other') }}<BR/>
						<div id="hiddenOtherExpand" style="display: none">
							<BR/>
							{{ Form::label('otherExpand', 'Please expand on your choice of OTHER') }}<BR/>
							{{ Form::textarea('otherExpand', $otherExpand) }}
						</div>
					</div>
				</div>
				<div id="question3" class="question">
					<div class="textblock">
						<H1>Step 3: <img src="img/glyphs/image_questionmark-02.png" width="30px" title="{{$responseLabel[8]}}"></H1>
						<div class="gameInstructionText">What best describes the image sharpness quality</div>
						{{ Form::radio('qualityDescription', 'good', false, ['id' => 'good', 'class' => 'qualityDescription', 'onClick' => 'updateDisabledSubmitButtonVesEx(), calculateProgressPercentage()', 'required'=>'required' ]) }}
						{{ Form::label('good', 'Good') }} <BR/>
						{{ Form::radio('qualityDescription', 'medium', false, ['id' => 'medium', 'class' => 'qualityDescription', 'onClick' => 'updateDisabledSubmitButtonVesEx(), calculateProgressPercentage()', 'required'=>'required' ]) }}
						{{ Form::label('medium', 'Medium') }} <BR/>
						{{ Form::radio('qualityDescription', 'poor', false, ['id' => 'poor', 'class' => 'qualityDescription', 'onClick' => 'updateDisabledSubmitButtonVesEx(), calculateProgressPercentage()', 'required'=>'required' ]) }}
						{{ Form::label('poor', 'Poor') }} <BR/>
						{{ Form::radio('qualityDescription', 'blankImage', false, ['id' => 'blankImage', 'class' => 'qualityDescription', 'onClick' => 'updateDisabledSubmitButtonVesEx(), calculateProgressPercentage()', 'required'=>'required' ]) }}
						{{ Form::label('blankImage', 'Blank (Black) Image') }}<BR/>
						{{ Form::radio('qualityDescription', 'noImage', false, ['id' => 'noImage', 'class' => 'qualityDescription', 'onClick' => 'updateDisabledSubmitButtonVesEx(), calculateProgressPercentage()', 'required'=>'required' ]) }}
						{{ Form::label('noImage', 'No Image') }}
					</div>						
				</div>
				<div id="question4" class="question">
					<div class="textblock">
						<H1>Would you like to make any comments on this image? [Optional]</H1>
						<div id="commentForm">
							<div class="gameInstructionText">Thank you for providing relevant information. Please make your comments here:</div>
							{{ Form::textarea('comment', $comment, ['id' => 'comment','placeholder' => 'Please enter your comments here.', 'onkeypress' => 'calculateProgressPercentage()']) }}
						</div>
						{{ Form::submit('Finish', ['id' => 'disabledSubmitButtonVesEx', 'class' => 'goFinish', 'onClick' => 'putUnansweredQuestionOnTop()']) }}
					</div>
				</div>
				<div>
					<button type="button" style="width: 80px;" id="previousQuestionButton" class="bioCrowdButton goPreviousQuestion">Previous</button>
					<button type="button" style="float:right; width: 80px;" id="MovingNextQuestionButtonBigScreen" class="bioCrowdButton goNextQuestion">Next</button>	
				</div>
			</div>
			<div class="col span_3_of_8" id="logic_container" align="center">
				<table style="height:100%;">
					<tr>
						<td>
							<img id="annotatableImage" src="{{ $image }}" width="100%" />
						</td>
					</tr>
				</table>
			</div>
			<div class="col span_8_of_8">
				<div style="float:right;" id="skipImageDiv">Want to skip this image?&nbsp;&nbsp;
					{{ Form::submit('Skip image', ['id' => 'skipImageButton','class' => 'goNextImage bioCrowdButton', 'onClick' => 'makeQuestionsNonRequired(), flagThisTask()', 'title' => 'Want to skip this image? Click here for the next one']) }}</div>
				</div>
			</div>
		</div>
		<div class="section group" id="completed_game_container">
		</div>

	</div>

</div>

<div id="completed_game_popup" align="center">
	<div>
		<table id="table_completed_game_text" style=" margin: 25px; padding: 10px; border: 1px solid #b5b7bb; border-radius: 12px;">
			<tr>
				<td colspan="3"><span style="color: #89c33f; font-size: 48px; font-family: 'Lubalin for IBM'; font-weight: 600;">Congratulations!</span></td>
			</tr>
			<tr>
				<td colspan="3"><span style="color: #003f69; font-size: 36px; font-family: 'Lubalin for IBM'; font-weight: 600;">You finished the game</span></td>
			</tr>
			<tr  style="color: #003f69; font-size: 28px; font-family: 'Helvetica Neue'; font-weight: bold;">
				<td><span>You received a score of:</span></td>
				<td><span>{{Session::get('score')}}</span></td>
				<!-- td rowspan="2">+5</td>
			</tr>
			<tr  style="color: #003f69; font-size: 28px; font-family: 'Helvetica Neue'; font-weight: bold;">
				<td><span>Crowd:</span></td>
				<td><span>35</span></td -->
			</tr>
			@if(Session::get('campaignScoreTag'))
				<tr>
					<td colspan="3"><span style="color: #003f69; font-size: 36px; font-family: 'Lubalin for IBM'; font-weight: 600;">You finished the campaign {{Session::get('campaignScoreTag')['campaignTag']}}</span></td>
				</tr>
				<tr style="color: #003f69; font-size: 28px; font-family: 'Helvetica Neue'; font-weight: bold;">
					<td><span>You received a score of:</span></td>
					<td><span>{{Session::get('campaignScoreTag')['campaignScore']}}</span></td>
				</tr>
			@endif
			@if(strpos(Auth::user()->get()->email, '@anonymous-user.com'))
				<tr>
					<td><span>Your score will only be saved for the duration of this session. </span></td>
				</tr>
				<tr>
					<td><span>If you want to keep your progress after the session ends, please set your e-mail and password on the <a href="profile">profile</a> page. </span></td>
				</tr>
			@endif
		</table>
		<div align="center"> 
			<a href="#"><img src="img/glyphs/logo_twitter.png" height=45px></img></a>
			<a href="#"><img src="img/glyphs/logo_facebook.png" height=45px></img></a>
			<span  style="color: #003f69; font-size: 18px; font-family: 'Helvetica Neue'; font-weight: bold;">Share my results</span>
		</div>
		<table  id="table_completed_game_buttons">
			<tr>
				<td style="width: 33%; text-align: center;"><button type="button" id="playAgainButton" class="goPlayAgain" onclick="location.href='#'">Play Again</button></td>
				<td style="width: 33%; text-align: center;"><a href="{{ Lang::get('gamelabels.gameUrl') }}"><button type="button" id="selectAnotherGameButton" class="goGameSelect">Select Other Game</button></a></td>
				<td style="width: 33%; text-align: center;"><button type="button" id="crowdResultsButton" class="goCrowdData" onclick="location.href='#.html'">Crowd Results</button></td>
			</tr>
		</table>				 
	</div>
</div>

@stop
