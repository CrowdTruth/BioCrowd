@extends('baseGameView')

@section('extraheaders')
<link href="/css/cellEx.css" rel="stylesheet">
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
	
	/**
	* Update the shape selection to rectangle or Ellipse
	*/
	function updateShapeSelection(shape) {
		if(shape == 'rectangle') {
			ct_annotate.doRectangle(true);
			ct_annotate_draw();
		} else {
			ct_annotate.doRectangle(false);
			ct_annotate_draw();
		}
	}
	
	/**
	 * Prepare response to be submitted.
	 */
	function prepareResponse() {
		response = ct_annotate.getAnnotations();
		response = JSON.stringify(response);
		$('#response').val(response);
		calculateProgressPercentage();
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
		
		ct_annotate.loadCanvasImage(canvas, '{{ $image }}', doRect, styleDrag, styleFixed);
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
		$(document).ready(function() {
			$('#selector_circle').on({
			    'click': function(){
			        if (!$(this).hasClass("active_selector")) {        	 
			            $(this).attr('src','img/game_images/images_question-03.png');
			            $(this).addClass("active_selector")
			            $('#selector_square').attr('src','img/game_images/images_question-02.png') 
			            $('#selector_square').removeClass("active_selector")
			        }
			    }
			});
			
			$('#selector_square').on({
			    'click': function(){
			        if (!$(this).hasClass("active_selector")) {        	 
			            $(this).attr('src','img/game_images/images_question-01.png');
			            $(this).addClass("active_selector")
			            $('#selector_circle').attr('src','img/game_images/images_question-04.png')  
			             $('#selector_circle').removeClass("active_selector")
			        }
			    }
			});
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

		//add function to open the examples popup
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

		//add function to close the examples popup
		$('.closeExamples').on({
			'click' : function() {
				$('.examplePopup').hide();
			}
		});

		//add function to back to marking button to hide current question div and show first question div	
		$('.goMarking').on({
			'click' : function() {
				$('.question_active').hide().removeClass(function() {
					$('#question1').addClass('question_active').show();
					$('.goMarking').hide();
					$('.goPreviousQuestion').hide();
					if ($(this).is("#question5")) {
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

					if ($(this).is("#question4")) {
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
					} else if ($(this).is("#question5")) {
						$('#question4').addClass('question_active').show();
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
                        scrollTop: $("#dropdown_container").offset().top
                    },2000);
			    }
			});
			
			$('.viewTutorial').on({
				'click':function(){    	
			    	$('#info_container').slideDown(500);
			    	$('#closeTutorial').show();	    	
				}	
			})
		
			$('.closeTutorial').on({
				'click':function(){    	
			    	$('#info_container').slideUp(500);		    	
			    	$('#dropdown_container').slideDown(500);
				}	
			})
			
			
		});
	</script>
	
	<script>
		$(document).ready( function() {
		  var el = $('#cell_number');
		  function change( amt ) {
		    el.val( parseInt( el.val(), 10 ) + amt );
		  }
		
		  $('#add').click( function() {
			if(el.val() == ""){
				el.val(0);
			}
		    change( 1 );
		    calculateProgressPercentage();
		  } );
		  $('#remove').click( function() {
			if(el.val() == ""){
				el.val(0);
			}
		    change( -1 );
		    calculateProgressPercentage();
		  } );
		  
		  $('#add').mouseup(function () {
			  $('#add').attr('src','img/game_images/images_question-06.png')
			}).mousedown(function () {
				  $('#add').attr('src','img/game_images/images_question-05.png')
			});
		
		  $('#remove').mouseup(function () {
			  $('#remove').attr('src','img/game_images/images_question-08.png')
			}).mousedown(function () {
				  $('#remove').attr('src','img/game_images/images_question-07.png')
			});
		  
		} );
	</script>
	
	<script>
		$('.startgame').on({
		    'click': function(){ 	
		    	$('#info_container').slideUp(500);		    	
		    	$('#dropdown_container').slideDown(500);
		    	$('.closeTutorial').show();
		    	$('.startgame').hide();
				$('#ribbon').css({
					"height" : "200px",
				});
		    }
		});
	</script>
	
	<script>
		function calculateProgressPercentage(){
			var totalAmountOfQuestions = 5;
			var calculateAnsweredFormItems = this.calculateAnsweredFormItems();
			document.getElementById("cellExProgressBar").innerHTML = ((calculateAnsweredFormItems.length/totalAmountOfQuestions)*100)+'\%';
			document.getElementById("cellExProgressBar").style.width = ((calculateAnsweredFormItems.length/totalAmountOfQuestions)*100)+'\%';
		}
		
		function calculateAnsweredFormItems() {
			var calculatedAnswerArray = new Array();
			
			if(document.getElementById("response").value != "[]" || document.getElementById("noCells").checked){
				calculatedAnswerArray.push(1);
			}

			var markingDescriptionCheckBoxes = document.getElementsByClassName('markingDescription');

			for (var i = 0; i < markingDescriptionCheckBoxes.length; i++) {
				if(markingDescriptionCheckBoxes[i].checked){
					calculatedAnswerArray.push(2);
				}        
			}

			if(document.getElementById("cell_number").value != ""){
				calculatedAnswerArray.push(3);
			}

			var qualityCheckBoxes = document.getElementsByClassName('qualityDescription');

			for (var i = 0; i < qualityCheckBoxes.length; i++) {
				if(qualityCheckBoxes[i].checked){
					calculatedAnswerArray.push(4);
				}        
			}
			
			var commentsCheckBoxes = document.getElementsByClassName('commentsFormItem');

			for (var i = 0; i < commentsCheckBoxes.length; i++) {
				if(commentsCheckBoxes[i].checked){
					calculatedAnswerArray.push(5);
				}        
			}
			
			return calculatedAnswerArray;
		}
		</script>
		
		<script>
		debugger;
		//hide current question div and show first required empty question form div
		function putUnansweredQuestionOnTop() {
			var totalAmountOfQuestions = 5;
			var calculateAnsweredFormItems = this.calculateAnsweredFormItems();
			$('.question_active').hide().removeClass(function() {
			debugger;
				for(var i = 2; i <= totalAmountOfQuestions; i++) {
					var itsInThere = calculateAnsweredFormItems.indexOf(i);
					if (itsInThere == -1){
						$('#question'+i).addClass('question_active').show();
						if(i != totalAmountOfQuestions) {
							$('.goNextQuestion').show();
							$('.goFinish').hide();
						}
						return 'question_active';
					}
				}
			})
		}
	</script>
	
@stop

@section('gameForm')
<div class="section group" id="dropdown_container">
	<div class="col span_8_of_8">
		<div class="section group" id="progress_container">
			<div class="col span_8_of_8">
				<div id="game_progress">
					<div class="bar" id="cellExProgressBar" style="width: 0%;">0%</div>						
				</div>
				<div align="center"><button type='button' class="viewTutorial">Tutorial</button></div>
			</div>		
		</div>

		<div class="section group" id="game_container">
			<div class="col span_3_of_8" id="question_container">
				<form action="">
					<div id="question1" class="question question_active">
						<div class="textblock">
							<H1>Step 1: {{$responseLabel[0]}} <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<span>By clicking on it or drawing a shape around it</span>
						</div>
						<BR>
						<div align="center">
							<ul id="selector" style="-webkit-margin-before: 0em; -webkit-margin-after: 0em;">
							<li><img id="selector_circle"  src="img/game_images/images_question-03.png" alt="circular selection"  width="100px" class="active_selector" onClick='updateShapeSelection("ellipse")'></li>
							<li><img id="selector_square" src="img/game_images/images_question-02.png" alt="square selection" width="100px" onClick='updateShapeSelection("rectangle")'></li>
							</ul>
						</div>
						<div style="display:none;">
							{{ Form::hidden('gameId', $gameId) }}
							{{ Form::hidden('taskId', $taskId) }}
							{{ Form::hidden('response','', [ 'id' => 'response' ] ) }}
						</div>
					</div>
					<div id="question2" class="question" >
						<div class="textblock">
							<H1>Step 2 <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<div id="markingDescription">
								{{ Form::radio('markingDescription', 'allCells', false , ['id' => 'allCells', 'class' => 'markingDescription', 'onClick' => 'updateAnnotationCount();expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ] ) }}
								{{ Form::label('allCells', $responseLabel[1]) }} <BR/>
								{{ Form::radio('markingDescription', 'tooManyCells', false , ['id' => 'tooManyCells', 'class' => 'markingDescription', 'onClick' => 'updateAnnotationCount();expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
								{{ Form::label('tooManyCells', $responseLabel[2]) }} <BR/>
								{{ Form::radio('markingDescription', 'noCells', false , [ 'id' => 'noCells', 'class' => 'markingDescription', 'onClick' => 'updateAnnotationCount();expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
								{{ Form::label('noCells', $responseLabel[3]) }} <BR/>
								{{ Form::radio('markingDescription', 'other', false , [ 'id' => 'other', 'class' => 'markingDescription', 'onClick' => 'updateAnnotationCount();expandOtherTextArea(), calculateProgressPercentage();', 'required'=>'required' ]) }}
								{{ Form::label('other', $responseLabel[4]) }}<BR/>
								<div id="hiddenOtherExpand" style="display: none">
									<BR/>
									{{ Form::label('otherExpand', 'Please expand on your choice of OTHER') }}<BR/>
									{{ Form::textarea('otherExpand') }}
								</div>
							</div>
						</div>
					</div>
					<div id="question3" class="question">
						<div class="textblock">
							<H1>Step 3 <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<span>{{$responseLabel[5]}}</span>
						</div>
						<div align="center">
						<ul id="cell_counter" style="-webkit-margin-before: 0em; -webkit-margin-after: 0em;">
						<li><img id="remove" class="cell_increment" src="img/game_images/images_question-08.png" alt="increment down" width="30px"></li>
						<li>{{Form::number('totalCells','',['required', 'id' => 'cell_number'])}}</li>
						<li><img id="add" class="cell_increment"  src="img/game_images/images_question-06.png" alt="increment up"  width="30px"></li>
						</ul>
						</div>							
					</div>
					<div id="question4" class="question">
						<div class="textblock">
							<H1>Step 4: What best describes the image quality <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							<div>Image Sharpness</div>
							{{ Form::radio('qualityDescription', 'good', false, ['id' => 'good', 'class' => 'qualityDescription', 'onClick' => 'updateAnnotationCount(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('good', 'Good') }} <BR/>
							{{ Form::radio('qualityDescription', 'medium', false, ['id' => 'medium', 'class' => 'qualityDescription', 'onClick' => 'updateAnnotationCount(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('medium', 'Medium') }} <BR/>
							{{ Form::radio('qualityDescription', 'poor', false, ['id' => 'poor', 'class' => 'qualityDescription', 'onClick' => 'updateAnnotationCount(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('poor', 'Poor') }} <BR/>
							{{ Form::radio('qualityDescription', 'blank', false, [ 'id' => 'blankImage', 'class' => 'qualityDescription', 'onClick' => 'updateAnnotationCount(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('blankImage', 'Blank (Black) Image') }}<BR/>
							{{ Form::radio('qualityDescription', 'noImage', false, [ 'id' => 'noImage', 'class' => 'qualityDescription', 'onClick' => 'updateAnnotationCount(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('noImage', 'No Image') }}
						</div>				
					</div>
					<div id="question5" class="question">
						<div class="textblock">
							<H1>Optional: Would you like to make any comments on this image? <img src="img/glyphs/image_questionmark-02.png" width="30px" title="insert additional information here"></H1>
							{{ Form::radio('comments', 'yesComments', false, ['id' => 'commentFormPlease', 'class' => 'commentsFormItem', 'onClick' => 'showCommentForm(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('commentFormPlease', 'Yes') }} <BR/>
							{{ Form::radio('comments', 'noComments', false, ['id' => 'noCommentFormPlease', 'class' => 'commentsFormItem', 'onClick' => 'showCommentForm(), calculateProgressPercentage();', 'required'=>'required' ]) }}
							{{ Form::label('noCommentFormPlease', 'No') }} <BR/>	
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
						<td style="width: 33%; text-align: center;"><button type='button' class="goNextQuestion">Next Question</button>{{ Form::submit('Finish', ['id' => 'disabledSubmitButton', 'class' => 'goFinish', 'onClick' => 'putUnansweredQuestionOnTop(), prepareResponse();' ]) }}</td>
					</tr>
				</table>						
			</div>
			<div class="col span_2_of_8" id="question_container">
				<div class="ct_object_list" style="display:block">
					<div class="path_list_title_container">
						<div class="path_list_title_text">Drawn</div>
					</div>
					<div class="ct_menuItem_list" id="ct_menuItem_list" style="display:block">
						<div class="ct_empty_menuItem_list">0 Drawn</div>
					</div>
				</div>
				{{ Form::button('Remove last', ['type' => 'button','onClick' => 'ct_annotate.removeLast()']) }}
			</div>
			<div class="col span_3_of_8" id="logic_container" align="center">
				<canvas id="annotationCanvas"></canvas>
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
