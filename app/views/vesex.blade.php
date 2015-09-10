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

	//set the border color of all children to white
	var childrenArray = document.getElementById("vesicleClassificationButtonsList").children;
	for(var	i=0; i<childrenArray.length; i++){
		childrenArray[i].style.border = "1px solid #17ad4b";
	}

	//set the border of the clicked item to blue
	document.getElementById(value).style.border = "1px solid #369";
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
		$('#skipImageDiv').hide();
		
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
			$('.question_active').hide();
			$('#info_container').hide();
			$('#completed_game_popup').show();
		} else {
			$('#completed_game_popup').hide();
		}

		$( window ).load(function() {
			if(flag == "skipped"){
				$('#info_container').slideUp(500);		    	
		    	$('#dropdown_container').slideDown(500);
		    	$('.closeTutorial').show();
		    	$('.startgame').hide();
		    	$('#skipImageDiv').show();
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
					$('.openExamples').text('Show Examples');
				} else {
					$('.examplePopup').show();
					$('.openExamples').text('Hide Examples');
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
		
		//add function to back to marking button to hide current question div and show first question div	
		$('.goPlayAgain').on({
			'click' : function() {	
				$('#question1').addClass('question_active').show();
				$('#dropdown_container').show();
				$('#completed_game_container').hide();
				$('#completed_game_popup').hide();	
				$('#skipImageDiv').show();	
			}
		});
	})
</script>
	
	<script>
		$(document).ready(function() {
			$('#dropdown_container').hide();
			$('.closeTutorial').hide();	
			
			$('.startgame').on({
			    'click': function(){ 	
			    	$('#info_container').slideUp(500);		    	
			    	$('#dropdown_container').slideDown(500);
			    	$('.closeTutorial').show();
			    	$('.startgame').hide();
			    	$('#skipImageDiv').show();
					$('#ribbon').css({
						"height" : "200px",
					});
					$('html, body').animate({
                        scrollTop: $("#ribbon").height()+$("#banner").height()
                    },2000);
			    }
			});
			
			//add function to open and close the tutorial popup
			$('.openCloseTutorial').on({
				'click' : function() {
					if ($('#info_container').is(':hidden')) {
						$('#info_container').slideDown(500);
						$('.openCloseTutorial').text('Close Tutorial');
					} else {
						$('#info_container').slideUp(500);
						$('#dropdown_container').slideDown(500);
						$('.openCloseTutorial').text('Open Tutorial');
					}
				}
			});
		});
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
		var campaignIdArray = JSON.stringify("<?php echo serialize($campaignIdArray);?>");
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
				<div align="center"><button type='button' class="openCloseTutorial">Open Tutorial</button></div>
			</div>		
		</div>
		<div class="section group" id="game_container">
			<div class="col span_3_of_8" id="logic_container" align="center">
				<table style="height:100%;">
					<tr>
						<td style="width:1%;"><button type="button" style="width: auto;" class="bioCrowdButton goPreviousQuestion"><</button></td>
						<td>
							<img id="annotatableImage" src="{{ $image }}" width="100%" />
						</td>
						<td style="width:1%;"><button type="button" style="width: auto;" id="MovingArrowButtonSmallScreen" class="bioCrowdButton goNextQuestion">></button></td>
					</tr>
				</table>
			</div>
			<div class="col span_5_of_8" id="question_container">
				<form action="">
					<table>
						<tr>
							<td>
								<div id="question1" class="question question_active" style="display:inline-block;">
									<div class="textblock">
										<H1>Step 1: Classify this picture <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
										<span>{{$responseLabel[0]}}</span>
									</div>
									<BR>
									<div class='vesicleClassificationButtons'>
										<ul id='vesicleClassificationButtonsList'> <!-- TO DO: make the images vertical align bottom, so the BRs are no longer necessary -->
											<li width='88px' id='clustered'>Clustered<BR/><img style='bottom: 5px; padding:5px' src='img/VesExButtonIcons/VesClusterIcon.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("clustered"), calculateProgressPercentage()'></li>
											<li width='88px' id='tip'>Tip<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesTipIcon.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("tip"), calculateProgressPercentage()'></li>
											<li width='88px' id='fog'>Fog<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesFogIcon.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("fog"), calculateProgressPercentage()'></li>
											<li width='88px' id='sideNucleus'>{{$responseLabel[1]}}<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesSideNucleusIcon.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("sideNucleus"), calculateProgressPercentage()'></li>
											<li width='88px' id='ringAroundNucleus'>{{$responseLabel[2]}}<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesRingIcon.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("ringAroundNucleus"), calculateProgressPercentage()'></li>
											<li width='88px' id='black'>Black<BR/><img style='padding:5px' src='img/VesExButtonIcons/Black.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("black"), calculateProgressPercentage()'></li>
											<li width='88px' id='noneOfThese'>None of these<BR/><img id='noneOfThese' style='padding:5px' src='img/VesExButtonIcons/NoneOfThese.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("noneOfThese"), calculateProgressPercentage()'></li>
											<li width='88px' id='noImage'>No image<BR/><img style='padding:5px' src='img/VesExButtonIcons/NoImage.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("noImage"), calculateProgressPercentage()'></li>
											<li width='88px' id='dontKnow'>I don't know<BR/><img style='padding:5px' src='img/VesExButtonIcons/DontKnow.jpg' onmousedown='setLocationWasChangedToTrue(), setFormItem("dontKnow"), calculateProgressPercentage()'></li>
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
										<H1>Step 2 <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
										<div>Please select all which apply to your selection from STEP 1.</div>
										{{ Form::radio('markingDescription', 'allVesicles', false, ['id' => 'allVesicles', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), updateDisabledSubmitButtonVesEx(), calculateProgressPercentage();', 'required'=>'required' ]) }}
										{{ Form::label('allVesicles', $responseLabel[3]) }}<BR/>
										{{ Form::radio('markingDescription', 'mixed', false, ['id' => 'mixed', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), updateDisabledSubmitButtonVesEx(), calculateProgressPercentage();', 'required'=>'required' ]) }}
										{{ Form::label('mixed', $responseLabel[4], false, ['required'=>'required' ]) }}<BR/>
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
										<H1>Step 3: What best describes the image quality <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
										<div>Image Sharpness</div>
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
										<H1>Optional: Would you like to make any comments on this image? <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
										<div id="commentForm">
											{{ Form::label('comment', 'Thank you for providing relevant information. Please make your comments here:') }}<BR/>
											{{ Form::textarea('comment', $comment, ['placeholder' => 'Please enter your comments here.', 'onkeypress' => 'calculateProgressPercentage()']) }}
										</div>
										{{ Form::submit('Finish', ['id' => 'disabledSubmitButtonVesEx', 'class' => 'goFinish', 'onClick' => 'putUnansweredQuestionOnTop()']) }}
									</div>
								</div></td>
							<td style="width:1%;"><button type="button" style="width: auto;" id="MovingArrowButtonBigScreen" class="bioCrowdButton goNextQuestion">></button></td>
						</tr>
					</table>				
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
				</tr style="color: #003f69; font-size: 28px; font-family: 'Helvetica Neue'; font-weight: bold;">
					<td><span>You received a score of:</span></td>
					<td><span>{{Session::get('campaignScoreTag')['campaignScore']}}</span></td>
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
				<td style="width: 33%; text-align: center;"><button type="button" class="goPlayAgain" onclick="location.href='#'">Play Again</button></td>
				<td style="width: 33%; text-align: center;"><button type="button" class="goGameSelect"  onclick="location.href='/'">Game Select</button></td>
				<td style="width: 33%; text-align: center;"><button type="button" class="goCrowdData"  onclick="location.href='#.html'">Crowd Results</button></td>
			</tr>
		</table>				 
	</div>

</div>

<div class="section group">
	<div class="col span_8_of_8">
		<table style="width:100%">
			<tr style="width:100%">
				<td style="width: 20%; text-align: left;"><button type="button" class="goHome bioCrowdButton" title="Back to Crowdtruth Games" onclick="location.href='http://game.crowdtruth.org'">Crowdtruth Games</button></td> <!-- TODO: make this url and the name of "Crowdtruth Gams" a parameter -->
				<td style="width: 20%; text-align: left;"><button type="button" class="goGameSelect bioCrowdButton" title="Back to game select" onclick="location.href='{{ Lang::get('gamelabels.gameUrl') }}'">Game Select</button></td>			
				<td style="width: 60%; text-align: right;"><div id="skipImageDiv">Want to skip this image?&nbsp;&nbsp;
				{{ Form::submit('Next image', ['class' => 'goNextImage bioCrowdButton', 'onClick' => 'makeQuestionsNonRequired(), flagThisTask()', 'title' => 'Want to skip this image? Click here for the next one']) }}</div></td>
			</tr>
		</table>
	</div>	
</div>

</form>

@stop
