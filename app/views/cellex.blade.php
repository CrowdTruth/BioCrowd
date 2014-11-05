@extends('layout')

@section('extraheaders')
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
	<script>
		var list = ["<tr><td>Agreement +10</tr>","<tr><td>Pioneer +10</td></tr>","<tr><td style='color:green'>Three in a row +10</td></tr>","<tr><td style='color:orange'>Flawless +50</td></tr>"];
		
		function answerEvaluation() {
			var score_body = '';
			var agreement_counter = 0;
			var userScore = 0;

			
			var win = Math.floor(Math.random() * ((10-2)+1) + 2);
			//if correct answer radio set 1, then +10 agreement points
			if(win>2){score_body+=list[0]; agreement_counter++; userScore +=10;}//else{score_body+=list[1];}
			if(win>6){score_body+=list[0]; agreement_counter++; userScore +=10;}//else{score_body+=list[1];}
			if(win>10){score_body+=list[0]; agreement_counter++; userScore +=10;}//else{score_body+=list[1];}
			if(win>8){score_body+=list[0]; agreement_counter++; userScore +=10;}//else{score_body+=list[1];}
			if(win>4){score_body+=list[0]; agreement_counter++; userScore +=10;}//else{score_body+=list[1];}
			
			
			//if three in a row give bonus points
			if(agreement_counter > 2){score_body+=list[2]; userScore +=10;}
			
	
			
			var score_list_start = '<table>';
			var score_list_body = score_body;
			var score_list_end = '</table>';
			
			//write the score
			document.getElementById('userScore').innerHTML = userScore;
			document.getElementById('breakdownScore').innerHTML = score_list_start+score_list_body+score_list_end;
		};
		
		function checkIfWinner() {
			var crowdScore=40;
			var userScore= document.getElementById('userScore').innerHTML;
			if(userScore >= crowdScore){
				window.location = "win_page.html";
			} else { 
				window.location = "lose_page.html";
			}
		};

		var alphas = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		var digits = "0123456789";

		function isAlpha(c) {
			return (alphas.indexOf(c) != -1);
		}
		function isDigit(c) {
			return (digits.indexOf(c) != -1);
		}
		function isAlphaNum(c) {
			return (isAlpha(c) || isDigit(c));
		}
		function sortNumber(a, b) {
			return a - b;
		}
		function capitalizeTerm(sentence, b, e) {
			return sentence.substring(0, b)
					+ sentence.substring(b, e).toUpperCase()
					+ sentence.substring(e, sentence.length - 1);
		}
		function getSeedTermSpan(sentence, termsInFactor, noWords, b) {
			var index = new Array();
			index.push(b);

			if (noWords > 1) {
				for (i = 1; i < noWords; i++) {
					index.push(parseInt(parseInt(index[i - 1]))
							+ termsInFactor[i - 1].length + 1);
				}
			}
			return index;
		}
		function getInitialSpan(sentence, termsInFactor, noWords, b) {
			var index = new Array();
			index.push(b);

			for (i = 0; i < 3; i++) {
				var pos = parseInt(index[i]) - 1;

				// eliminate whitespace
				while (pos >= 0
						&& (sentence.charAt(pos) == ' '
								|| sentence.charAt(pos) == '-'
								|| sentence.charAt(pos) == '/' || sentence
								.charAt(pos) == '|')) {
					pos--;
				}

				while (pos >= 0 && sentence.charAt(pos) != ' '
						&& sentence.charAt(pos) != '-'
						&& sentence.charAt(pos) != '/'
						&& sentence.charAt(pos) != '|') {
					pos--;
				}
				pos++;
				if (pos >= 0)
					index.push(pos);
			}
			index = index.sort(sortNumber);

			if (noWords > 1) {
				for (i = 1; i < noWords; i++) {
					index.push(parseInt(parseInt(index[i + 2]))
							+ termsInFactor[i - 1].length + 1);
				}
			}

			index = index.sort(sortNumber);

			for (i = 0; i < 3; i++) {
				var pos = parseInt(index[index.length - 1]);
				while (pos < sentence.length && sentence.charAt(pos) != ' '
						&& sentence.charAt(pos) != '-'
						&& sentence.charAt(pos) != '/'
						&& sentence.charAt(pos) != '|') {
					pos++;
				}

				// eliminate whitespace
				while (pos < sentence.length
						&& isAlphaNum(sentence.charAt(pos)) == false)
					pos++;

				if (pos < sentence.length)
					index.push(pos);
			}

			return index;
		}
		function updateHighlightedWords(arrayId, indexSent) {
			arrayId.sort(function(a, b) {
				if (isNaN(a) || isNaN(b)) {
					if (a > b)
						return 1;
					else
						return -1;
				}
				return a - b;
			});
			var selection2 = "";
			for ( var i = 0; i < arrayId.length; i++) {
				var num = parseInt(arrayId[i]);
				var n = num.toString();
				if (indexSent == "one") {
					selection2 += document.getElementById("one" + n).innerHTML
							+ " ";
				}
				if (indexSent == "two") {
					selection2 += document.getElementById("two" + n).innerHTML
							+ " ";
				}
			}
			return selection2;
		}
		function printArray(array) {
			retValue = "";
			for (i = 0; i < array.length; i++) {
				retValue += array[i] + "-";
			}
			if (array.length != 0) {
				retValue = retValue.slice(0, -1);
			}
			return retValue;
		}
	</script>
	
	<style type="text/css">
	fieldset {
		padding: 10px;
		background: #fbfbfb;
		border-radius: 5px;
		margin-bottom: 5px;
	}
	
	.word_split1 {
		color: blue;
	}
	
	.word_split2 {
		color: blue;
	}
	</style>
@stop

@section('content')

<div id="popUpDiv" style="display: none; padding: 13px">
	<b style="font-size: 13px; color: black;">How would you like to
		unlock this article and add it to your collection?</b><br /> <a
		href="article.html"><button class='popupButton'
			style="width: 100%; font-size: 13px;" onclick="addedToCollection()">
			Read the article <br />cost: 10 <img class='coins'
				src='medical_logo.png'></img>
		</button></a> <a href="game_Interview_mockup_contributing_home.html"
		style="color: black;"><button class='popupButton'
			style="width: 100%; font-size: 13px;">
			Play games<br />earn more <img class='coins' src='medical_logo.png'></img>
		</button></a> <a href="question_answer.html"><button class='popupButton'
			style="width: 100%; font-size: 13px;">
			Annotate this article<br /> earn: 10 <img class='coins'
				src='medical_logo.png'></img>
		</button></a> <a href="#" id="closeLink"><b
		style="font-size: 14px; color: black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Close</b></a>
</div>

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
						<p>In the image below one or more cells are
							displayed. 
						</p>
						<p>CLICK on all the cells to DRAW A SQUARE AROUND THE CELL, 
							even if they are lying partly behind other cells/debris. 
							When two cells are overlapping, you may draw overlapping
							squares
						</p>
						<p>
							Examples of cells:
						</p>
						<img src="img/110803_a1b_ch00.png" width="200">
						<img src="img/110803_a2a_ch00.png" width="200">
						<img src="img/110803_a2b_ch00.png" width="200">
					</div>
				</div>
			<!-- End Instructions -->
  <div class="content customTask">
  <form>
  <input type= "hidden" name="totaltime">
  <input type= "hidden" name="tagtiming">
  <input type= "hidden" name="starttime">
  <input type= "hidden" name="endtime">
  <input type= "hidden" name="annotations">
  <input type= "hidden" name="fullsizeimageclicked" value="false">
  <input type= "hidden" name="contributors_browser" validates="user_agent">
  </form>
  
  <div class="row">
    <div class="span7">
      <a href="img/110803_a1_ch00.png" class="fullsizeimage" target="_blank" title="Click to open a full-size version in a new tab">Click for the full-size image</a><BR>
      <img class="annotatable" src="img/110803_a1_ch00.png" />
    </div>
    <div class="span4">
    <label>How many CELLS are in this image?*</label><br>
    <input type= "text" name="nrcells" validates="required positiveInteger" value="Number of cells" gold="true" style="color:#888;" onfocus="inputFocus(this)" onblur="inputBlur(this)"><br>
    <label class="instructions">Count every cell you see on the image. Click for the full-size image if needed.</label>
      <p>
      <div class="cml_field">
        <span class="legend">Tag each individual CELL by drawing a bounding box around it.</span><br>
        <label class="instructions">For each box fill in the fields of the popup.</label>
        <div class="border">
          Number of bounding boxes: <span class="nrTags">0</span>
        </div>
      </div>
      <br>
      <form action="" label="Certainty*" validates="required" class="confidence">
      Choose how certain you are of the correctness of the position of the cells?<br>
        <input type="radio" name="nrcelltags" label="0">0<br>
        <input type="radio" name="nrcelltags" label="1">1<br>
        <input type="radio" name="nrcelltags" label="2">2<br>
        <input type="radio" name="nrcelltags" label="3">3<br>
        <input type="radio" name="nrcelltags" label="4">4<br>
        <input type="radio" name="nrcelltags" label="5">5<br>
        <input type="radio" name="nrcelltags" label="6">6<br>
        <input type="radio" name="nrcelltags" label="More">More
      </form>
    </div>
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
