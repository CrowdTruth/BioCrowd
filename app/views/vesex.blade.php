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

	//set the border color of all children to white
	var childrenArray = document.getElementById("vesicleClassificationButtonsList").children;
	for(var	i=0; i<childrenArray.length; i++){
		childrenArray[i].style.border = "1px solid #17ad4b";
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
		$('.goMarking').hide();
		$('.goPreviousQuestion').hide();
		$('.goFinish').hide();
		$('#completed_game_container').hide();
		$('#completed_game_popup').hide();
		$('.examplePopup').hide();

		//var x = "<!--?php echo ( isset( $_POST['consecutiveGame'] ) && $_POST['consecutiveGame'] != '') ? $_POST['consecutiveGame'] : '';?-->";
		
		//if($(x) == ('consecutiveGame')){
		//	$('.question_active').hide();
		//	$('#info_container').hide();
		//	$('#completed_game_popup').show();
		//} else {
		//	$('#completed_game_popup').hide();
		//}

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

		//add function to back to marking button to hide current question div and show first question div	
		$('.goMarking').on({
			'click' : function() {
				$('.question_active').hide().removeClass(function() {
					$('#question1').addClass('question_active').show();
					$('.goMarking').hide();
					$('.goPreviousQuestion').hide();
					if ($(this).is("#question4")) {
						$('.goNextQuestion').show();
						$('.goFinish').hide();
					}
					return 'question_active';
				})
			}
		});

		//add function to next question button to hide current question div and show next question div
		$('.goNextQuestion').on({
			'click' : function() {
				$('.question_active').hide().removeClass(function() {

					if ($(this).is("#question3")) {
						$(this).next().addClass('question_active').show();
						$('.goNextQuestion').hide();
						$('.goFinish').show();
						return 'question_active';
					} else {
						$(this).next().addClass('question_active').show();
						$('.goMarking').show();
						$('.goPreviousQuestion').show();
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
						$('.goMarking').hide();
						$('.goPreviousQuestion').hide();
						return 'question_active';
					} else if ($(this).is("#question4")) {
						$('#question3').addClass('question_active').show();
						$('.goNextQuestion').show();
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
				$('.goMarking').hide();
				$('.goPreviousQuestion').hide();
				$('#completed_game_container').hide();
				$('#completed_game_popup').hide();
				$('.goFinish').hide();
				$('#game_container').show();
				$('.goNextQuestion').show();			
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
			
			if(document.getElementById("location").value != "undefined"){
				calculatedAnswerArray.push('1');
			}

			var markingDescriptionCheckBoxes = document.getElementsByClassName('markingDescription');

			for (var i = 0; i < markingDescriptionCheckBoxes.length; i++) {
				if(markingDescriptionCheckBoxes[i].checked){
					calculatedAnswerArray.push('2');
				}        
			}

			var qualityCheckBoxes = document.getElementsByClassName('qualityDescription');

			for (var i = 0; i < qualityCheckBoxes.length; i++) {
				if(qualityCheckBoxes[i].checked){
					calculatedAnswerArray.push('3');
				}        
			}
			
			var commentsCheckBoxes = document.getElementsByClassName('commentsFormItem');

			for (var i = 0; i < commentsCheckBoxes.length; i++) {
				if(commentsCheckBoxes[i].checked){
					calculatedAnswerArray.push('4');
				}        
			}
			
			return calculatedAnswerArray;
		}
	</script>
@stop

@section('gameForm')
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
			<div class="col span_5_of_8" id="question_container">
				<form action="">
					<div id="question1" class="question question_active" style="display:inline-block;">
						<div class="textblock">
							<H1>Step 1: Classify this picture <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<span>{{$responseLabel[0]}}</span>
						</div>
						<BR>
						<div class='vesicleClassificationButtons'>
							<ul id='vesicleClassificationButtonsList'> <!-- TO DO: make the images vertical align bottom, so the BRs are no longer necessary -->
								<li width='88px' id='clustered'>Clustered<BR/><img style='bottom: 5px; padding:5px' src='img/VesExButtonIcons/VesClusterIcon.jpg' onmousedown='setFormItem("clustered"), calculateProgressPercentage()'></li>
								<li width='88px' id='tip'>Tip<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesTipIcon.jpg' onmousedown='setFormItem("tip"), calculateProgressPercentage()'></li>
								<li width='88px' id='fog'>Fog<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesFogIcon.jpg' onmousedown='setFormItem("fog"), calculateProgressPercentage()'></li>
								<li width='88px' id='sideNucleus'>{{$responseLabel[1]}}<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesSideNucleusIcon.jpg' onmousedown='setFormItem("sideNucleus"), calculateProgressPercentage()'></li>
								<li width='88px' id='ringAroundNucleus'>{{$responseLabel[2]}}<BR/><img style='padding:5px' src='img/VesExButtonIcons/VesRingIcon.jpg' onmousedown='setFormItem("ringAroundNucleus"), calculateProgressPercentage()'></li>
								<li width='88px' id='black'>Black<BR/><img style='padding:5px' src='img/VesExButtonIcons/Black.jpg' onmousedown='setFormItem("black"), calculateProgressPercentage()'></li>
								<li width='88px' id='noneOfThese'>None of these<BR/><img id='noneOfThese' style='padding:5px' src='img/VesExButtonIcons/NoneOfThese.jpg' onmousedown='setFormItem("noneOfThese"), calculateProgressPercentage()'></li>
								<li width='88px' id='noImage'>No image<BR/><img style='padding:5px' src='img/VesExButtonIcons/NoImage.jpg' onmousedown='setFormItem("noImage"), calculateProgressPercentage()'></li>
								<li width='88px' id='dontKnow'>I don't know<BR/><img style='padding:5px' src='img/VesExButtonIcons/DontKnow.jpg' onmousedown='setFormItem("dontKnow"), calculateProgressPercentage()'></li>
							</ul>
					    </div>
						<div style="display:none;">
							{{ Form::hidden('gameId', $gameId) }}
							{{ Form::hidden('taskId', $taskId) }}
							{{ Form::hidden('response','', [ 'id' => 'response' ] ) }}
							{{ Form::hidden('location', '', ['id' => 'location']) }}
						</div>
					</div>
					<div id="question2" class="question" >
						<div class="textblock">
							<H1>Step 2 <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<div>Please select all which apply to your selection from STEP 1.</div>
							{{ Form::radio('markingDescription', 'allVesicles', false, ['id' => 'allVesicles', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('allVesicles', $responseLabel[3]) }}<BR/>
							{{ Form::radio('markingDescription', 'mixed', false, ['id' => 'mixed', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('mixed', $responseLabel[4], false, ['required'=>'required' ]) }}<BR/>
							{{ Form::radio('markingDescription', 'noCell', false, ['id' => 'noCell', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('noCell', $responseLabel[5]) }}<BR/>
							{{ Form::radio('markingDescription', 'other', false, [ 'id' => 'other', 'class' => 'markingDescription', 'onClick' => 'expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('other', 'Other') }}<BR/>
							<div id="hiddenOtherExpand" style="display: none">
								<BR/>
								{{ Form::label('otherExpand', 'Please expand on your choice of OTHER') }}<BR/>
								{{ Form::textarea('otherExpand') }}
							</div>
						</div>
					</div>
					<div id="question3" class="question">
						<div class="textblock">
							<H1>Step 3: What best describes the image quality <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<div>Image Sharpness</div>
							{{ Form::radio('qualityDescription', 'good', false, ['id' => 'good', 'class' => 'qualityDescription', 'onClick' => 'calculateProgressPercentage()', 'required'=>'required' ]) }}
							{{ Form::label('good', 'Good') }} <BR/>
							{{ Form::radio('qualityDescription', 'medium', false, ['id' => 'medium', 'class' => 'qualityDescription', 'onClick' => 'calculateProgressPercentage()', 'required'=>'required' ]) }}
							{{ Form::label('medium', 'Medium') }} <BR/>
							{{ Form::radio('qualityDescription', 'poor', false, ['id' => 'poor', 'class' => 'qualityDescription', 'onClick' => 'calculateProgressPercentage()', 'required'=>'required' ]) }}
							{{ Form::label('poor', 'Poor') }} <BR/>
							{{ Form::radio('qualityDescription', 'blank', false, ['id' => 'blank', 'class' => 'qualityDescription', 'onClick' => 'calculateProgressPercentage()', 'required'=>'required' ]) }}
							{{ Form::label('blank', 'Blank (Black) Image') }}<BR/>
							{{ Form::radio('qualityDescription', 'noImage', false, ['id' => 'noImage', 'class' => 'qualityDescription', 'onClick' => 'calculateProgressPercentage()', 'required'=>'required' ]) }}
							{{ Form::label('noImage', 'No Image') }}
						</div>						
					</div>
					<div id="question4" class="question">
						<div class="textblock">
							<H1>Optional: Would you like to make any comments on this image? <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							{{ Form::radio('comments', 'yesComments', false, ['id' => 'commentFormPlease', 'class' => 'commentsFormItem', 'onClick' => 'showCommentForm(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('comments', 'Yes') }} <BR/>
							{{ Form::radio('comments', 'noComments', false, ['id' => 'noCommentFormPlease', 'class' => 'commentsFormItem', 'onClick' => 'showCommentForm(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('comments', 'No') }} <BR/>	
							<div id="hiddenCommentForm" style="display: none">
								<BR/>
								{{ Form::label('comment', 'Thank you for providing relevant information. Please make your comments here:') }}<BR/>
								{{Form::textarea('comment', '', ['placeholder' => 'Please enter your comments here.'])}}
							</div>
						</div>				
					</div>
				</form>
				<table>
					<tr>				
						<td style="width: 33%; text-align: center;"><button type='button' class="goMarking">Back to Marking</button></td>
						<td style="width: 33%; text-align: center;"><button type='button' class="goPreviousQuestion">Previous Question</button></td>
						<td style="width: 33%; text-align: center;"><button type='button' class="goNextQuestion">Next Question</button>{{ Form::submit('Finish', ['id' => 'disabledSubmitButtonVesEx', 'class' => 'goFinish']) }}</td>
					</tr>
				</table>					
			</div>
			<div class="col span_3_of_8" id="logic_container" align="center">
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
				<td><span>You:</span></td>
				<td><span>40</span></td>
				<td rowspan="2">+5</td>
			</tr>
			<tr  style="color: #003f69; font-size: 28px; font-family: 'Helvetica Neue'; font-weight: bold;">
				<td><span>Crowd:</span></td>
				<td><span>35</span></td>
			</tr>
		</table>
		<div align="center"> 
			<a href="#"><img src="img/glyphs/logo_twitter.png" height=45px></img></a>
			<a href="#"><img src="img/glyphs/logo_facebook.png" height=45px></img></a>
			<span  style="color: #003f69; font-size: 18px; font-family: 'Helvetica Neue'; font-weight: bold;">Share my results</span>
		</div>
		<table  id="table_completed_game_buttons">
			<tr>
				<td style="width: 33%; text-align: center;"><button class="goPlayAgain" onclick="location.href='playGame?gameId={{ $gameId }}'">Play Again</button></td>
				<td style="width: 33%; text-align: center;"><button class="goGameSelect"  onclick="location.href='/'">Game Select</button></td>
				<td style="width: 33%; text-align: center;"><button class="goCrowdData"  onclick="location.href='#.html'">Crowd Results</button></td>
			</tr>
		</table>				 
	</div>

</div>

@stop
