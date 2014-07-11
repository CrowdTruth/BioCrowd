<!doctype html>
<html class="no-js">
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-theme.css" rel="stylesheet">
		<link href="css/gamestyle.css" rel="stylesheet">
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
				if(userScore >= crowdScore){window.location = "win_page.html";} else { window.location = "lose_page.html"; }
			};

		</script>

    </head>
       <body style="background:rgb(241, 235, 235);"><!--/background-image:url('http://www.medicine.usask.ca/acb/faculty/dr.-l.-dean-chapman/image010.jpg');<b style='font-family:Calibri;'><span style='font-size:20px;'><div style="height:8px;">&nbsp;</div>Total annotations: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12.000.342.3 <br />This weeks annotations: &nbsp;5.234</span></b>/-->
		
		<span class='header contentbox' style="background:#66B921 ; width:100%; position:absolute; height:95px;"></span>
		
		<div class='container' style='padding-top:0px'>
			
			<div class='row main'>
			
				<div class='header contentbox' style="background:none; border:none; ; width:100%; margin-left:10px;">
					<div class='col-xs-2' style='background:none'><a href="index.html"><img class='iconImg' src='medical_logo.jpg'></img></a></div>
							<div class='col-xs-5' style='background:none;'>
								<b style='font-family:Calibri;'><div style="height:5px;">&nbsp;</div><p><span style='font-size:24px; margin-left:-56px;'>Welcome to Dr. Watson,</span><br /><span style='font-size:20px; margin-left:-56px;'>the place to help yourself by helping others</span></p></b>
							</div>				
							<div class='col-xs-4' style='background:none;'>
								<b style='font-family:Calibri;'><span style='font-size:20px;'><div style="height:8px;">&nbsp;</div>12.000.342 annotations in total<br />&nbsp;&nbsp;&nbsp;&nbsp;5.234 annotations this week</span></b>
							</div>
							<a href src=""><h5 style="text-align:right; margin-top:2px; margin-bottom:-13px; color:black; font-size:15px;"><button class="logoutButton">logout</button></h5></a>
		</div>
				<div class='col-xs-12 headerspace' style="background:none; display:hidden; height:10px;"></div>
								
				<div id="blanket" style="display:none;"></div>
				<div id="popUpDiv" style="display:none; padding:13px">
					<b style="font-size:13px; color:black;">How would you like to unlock this article and add it to your collection?</b><br />
					<a href="article.html"><button class='popupButton' style="width:100%; font-size:13px;" onclick="addedToCollection()">Read the article <br />cost: 10 <img class='coins' src='medical_logo.png'></img></button></a>
					<a href="game_Interview_mockup_contributing_home.html" style="color:black;"><button class='popupButton' style="width:100%; font-size:13px;">Play games<br />earn more <img class='coins' src='medical_logo.png'></img> </button></a>
					<a href="question_answer.html"><button class='popupButton' style="width:100%; font-size:13px;">Annotate this article<br /> earn: 10 <img class='coins' src='medical_logo.png'></img></button></a>
					<a href="#" id="closeLink" ><b style="font-size:14px; color:black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Close</b></a>
				</div>	

				<div class='col-xs-9 maincolumn' style="background:none; width:72%">

				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
					<div class='row gameContent' style=" width:100%; height:100%;">

					<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->		
									<!-- Bootstrap v3.0.3 -->
<link href="https://s3.amazonaws.com/mturk-public/bs30/css/bootstrap.min.css" rel="stylesheet" />
<section class="container" style="padding: 10px 10px; font-family: Verdana, Geneva, sans-serif; color:#333333; font-size:0.9em;">
<div class="row col-md-12" style="width:76%;"><!-- Instructions -->
<div class="panel panel-primary">
<div class="panel-heading"><strong>Instructions</strong></div>

<div class="panel-body">
<p>In each of the sentences below a medical term is capitalized. Between the '<< >>' words that may make the term a more complete medical term are suggested.  
<p>CLICK on all the words to HIGHLIGHT THE MOST COMPLETE TERM (example: in the sentence "A man with << DIABETES mellitus >>" you would not only highlight the word diabetes, but also the word mellitus)</p>

<p>Examples of complete <strong>MEDICAL TERMS</strong>:</p>

<ul>
	<li>Prader-Willi syndrome</li>
	<li>partial anomalous venous drainage</li>
	<li>anomalous venous drainage</li>
	<li>preexisting schizophrenia</li>
	<li>schizophrenia</li>
</ul>
</div>
</div>
<!-- End Instructions --><!-- Content Body -->

<section>
<fieldset>
<p class="word_split1" onmousedown="answerEvaluation();" style='font-weight:bold'>Outcomes at school age after postnatal dexamethasone therapy for << LUNG DISEASE of prematurity >> </p></p></p>
<label style="font-weight:normal;">In the sentence above, do the words between << >>  complete the MEDICAL TERM --lung disease--? Remove or add words to create a complete MEDICAL TERM in the box below. </label> <input class="form-control" id="firstFactor" name="firstFactor" size="120" type="text" /> <input id="factor1" name="factor1" type="hidden" value="LUNG DISEASE" /> <input id="b1" name="b1" type="hidden" value="demaxemathasone" /> <input id="e1" name="e1" type="hidden" value="${e1}" /> <input id="sentence" name="sentence" type="hidden" value="${sentence}" /> <input id="wordId1" name="wordId2" type="hidden" value="" /> <input id="saveSelectionIds1" name="saveSelectionIds1" type="hidden" value="" /> <input id="confirmIds1" name="confirmIds1" type="hidden" value="" /></fieldset>

<fieldset>
<p class="word_split2" onmousedown="answerEvaluation();" style='font-weight:bold'> Outcomes at school age after << postnatal DEXAMETHASONE therapy >> for lung disease of prematurity</p>
<label style="font-weight:normal;">In the sentence above, do the words between << >> complete the MEDICAL TERM --dexamethasone--? Remove or add words to create a complete MEDICAL TERM in the box below. </label> <input class="form-control" id="secondFactor" name="secondFactor" size="120" type="text" /> <input id="factor2" name="factor2" type="hidden" value="${term2}" /> <input id="b2" name="b2" type="hidden" value="${b2}" /> <input id="e2" name="e2" type="hidden" value="${e2}" /> <input id="wordId2" name="wordId2" type="hidden" value="" /> <input id="saveSelectionIds2" name="saveSelectionIds2" type="hidden" value="" /> <input id="confirmIds2" name="confirmIds2" type="hidden" value="" /></fieldset>
</section>
<!-- End Content Body --><input class="btn btn-primary" id="submitButton" name="submit" type="Submit" value="Submit" onclick="checkIfWinner()"/></div>
</section>
<!-- close container -->
<style type="text/css">fieldset {
    padding: 10px;
    background:#fbfbfb;
    border-radius:5px;
    margin-bottom:5px;
}

.word_split1 {
 color:blue;
}
                 
.word_split2 {
 color:blue;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script>
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
  return sentence.substring(0, b) + sentence.substring(b, e).toUpperCase() + sentence.substring(e, sentence.length - 1);
}

function getSeedTermSpan(sentence, termsInFactor, noWords, b) {
  var index = new Array();
  index.push(b);
  
  if (noWords > 1) {
    for (i = 1; i < noWords; i ++) {
      index.push(parseInt(parseInt(index[i - 1])) + termsInFactor[i - 1].length + 1);
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
    while (pos >= 0 &&
           (sentence.charAt(pos) == ' ' ||
           sentence.charAt(pos) == '-' ||
           sentence.charAt(pos) == '/' ||
           sentence.charAt(pos) == '|')) {
      pos--;
    }
    
    while (pos >= 0 &&
           sentence.charAt(pos) != ' ' &&
           sentence.charAt(pos) != '-' &&
           sentence.charAt(pos) != '/' &&
           sentence.charAt(pos) != '|') {
      pos--;
    }
    pos++;
    if (pos >= 0) index.push(pos);
  }
  index = index.sort(sortNumber);
  // alert(index);
  
  if (noWords > 1) {
    for (i = 1; i < noWords; i ++) {
      index.push(parseInt(parseInt(index[i + 2])) + termsInFactor[i - 1].length + 1);
    }
  }
  
  index = index.sort(sortNumber);
  
  for (i = 0; i < 3; i++) {
    var pos = parseInt(index[index.length - 1]);
    while (pos < sentence.length  &&
           sentence.charAt(pos) != ' ' &&
           sentence.charAt(pos) != '-' &&
           sentence.charAt(pos) != '/' &&
           sentence.charAt(pos) != '|') {
      pos++;
    }
    
    // eliminate whitespace
    while (pos < sentence.length &&
           isAlphaNum(sentence.charAt(pos)) == false) pos++;
    
    if (pos < sentence.length) index.push(pos);
  }
  
  //alert(index);
  
  return index;
  
  /* id2 = getSeedTermSpan(sentence, termsInFactor, noWords, b);
  return index.filter(function(n) {
    return id2.indexOf(n) != -1
  });*/
}



//require(['jquery-noconflict'], function(jQuery) {
    
  //Ensure MooTools is where it must be
 /* Window.implement('$', function(el, nc){
    return document.id(el, nc, this.document);
  });
  var $ = window.jQuery;*/
  
  selectedIds1 = new Array();
  selectedIds2 = new Array();
  selectedConfirmIds1 = new Array();
  selectedConfirmIds2 = new Array();
  sentence1 = document.getElementsByClassName("word_split1");
  sentence2 = document.getElementsByClassName("word_split2");
  chooseFirstFactor = document.getElementById("firstFactor");
  chooseSecondFactor = document.getElementById("secondFactor");
  hiddenFieldId1 = document.getElementById('wordId1');
  hiddenFieldId2 = document.getElementById('wordId2');
  hiddenFieldFactor1 = document.getElementById('factor1');
  hiddenFieldFactor2 = document.getElementById('factor2');
  allIds1 = document.getElementById('saveSelectionIds1');
  allIds2 = document.getElementById('saveSelectionIds2');
  sentenceText = document.getElementById('sentence');
  b1 = document.getElementById('b1');
  b2 = document.getElementById('b2');
  e1 = document.getElementById('e1');
  e2 = document.getElementById('e2');
  noWordsFactor1 = 0;
  noWordsFactor2 = 0;
  sentence1Text = "";
  sentente2Text = "";
  
  var colorMap = {};
  colorMap["highlighted"] = "rgb(0, 255, 0)";
  colorMap["selected"] = "rgb(255, 255, 0)";
  colorMap["hovered"] = "rgb(128, 128, 128)";
     
  Array.prototype.remove = function(x) {
    for(i in this){
      if(this[i].toString() == x.toString()){
        this.splice(i,1);
      }
    }
  }
    
  Array.prototype.clear = function() {
    this.splice(0, this.length);
  };
  
	Array.prototype.contains = function(obj) {
		var i = this.length;
		while (i--) {
		    if (this[i] == obj) {
		        return true;
		    }
		}
		return false;
	};
  
// this code dynamically reformats the rows' labels to accomodate the quantity of text rendered in the rows' data cells
  $(document).ready(function(){
    chooseFirstFactor.readOnly = true;
    chooseSecondFactor.readOnly = true;
  //  confirmFirstFactor[0].readOnly = true;
  //  confirmSecondFactor[0].readOnly = true;
    hiddenFieldFactor1.value = hiddenFieldFactor1.value.toUpperCase();
    hiddenFieldFactor2.value = hiddenFieldFactor2.value.toUpperCase();
    
    noWordsFactor1 = hiddenFieldFactor1.value.split(/-| /).length;
    termsInFactor1 = hiddenFieldFactor1.value.split(/-| /);
    noWordsFactor2 = hiddenFieldFactor2.value.split(/-| /).length;
    termsInFactor2 = hiddenFieldFactor2.value.split(/-| /);
    
   /* index1 = new Array();
    index1.push(parseInt(b1.value));
    if (noWordsFactor1 > 1) {
      for (i = 1; i < noWordsFactor1; i ++) {
        index1.push(parseInt(parseInt(index1[i - 1])) + termsInFactor1[i - 1].length + 1);
      }
    }

    index2 = new Array();
    index2.push(parseInt(b2.value));
    if (noWordsFactor2 > 1) {
      for (i = 1; i < noWordsFactor2; i ++) {
        index2.push(parseInt(parseInt(index2[i - 1])) + termsInFactor2[i - 1].length + 1);
      }
    }*/
    
    $(".word_split1").text(capitalizeTerm($(".word_split1").text(), parseInt(b1.value), parseInt(e1.value)));
    sentence1Text = $(".word_split1").text();
    $(".word_split2").text(capitalizeTerm($(".word_split2").text(), parseInt(b2.value), parseInt(e2.value)));
    sentence2Text = $(".word_split2").text();
    //sentence2[0].value = capitalizeTerm(sentence2[0].value, parseInt(b2.value), parseInt(e2.value));
    
    index1 = getSeedTermSpan(sentence1Text, termsInFactor1, noWordsFactor1, parseInt(b1.value));
    index2 = getSeedTermSpan(sentence2Text, termsInFactor2, noWordsFactor2, parseInt(b2.value));
    //alert(index1);
    
    firstSpan1 = getInitialSpan(sentence1Text, termsInFactor1, noWordsFactor1, parseInt(b1.value));
    firstSpan2 = getInitialSpan(sentence2Text, termsInFactor2, noWordsFactor2, parseInt(b2.value));
   
    words = $(".word_split1").text().split(" ");
    $(".word_split1").empty();
    $.each(words, function(i, v) {
      if (v.indexOf("-") <= 0 && v.indexOf("/") <= 0) {
        $(".word_split1").append(" ");
        $(".word_split1").append($("<span class=\"word\">").text(v.substring(0, v.length)));
      }
      else {
        if (v.indexOf("-") > 0) {
            words2 = v.split("-");
        $(".word_split1").append(" ");
        for (i = 0; i < words2.length - 1; i ++) {
          $(".word_split1").append($("<span class=\"word\">").text(words2[i]));
          $(".word_split1").append("-");
        }
        $(".word_split1").append($("<span class=\"word\">").text(words2[words2.length-1]));
      }
      if (v.indexOf("/") > 0) {
        words2 = v.split("/");
        $(".word_split1").append(" ");
        for (i = 0; i < words2.length - 1; i ++) {
          $(".word_split1").append($("<span class=\"word\">").text(words2[i]));
          $(".word_split1").append("/");
        }
        $(".word_split1").append($("<span class=\"word\">").text(words2[words2.length-1]));
      }
    }
    });

    var startOffset = 0;
    w = $(".word_split1").find("span").each(function() {
      if ($(this).text() == "-" || $(this).text() == "/") {
        startOffset = startOffset - 1;
      }
      var i = sentence1Text.indexOf($(this).text(), startOffset);
      var wordType = "";
      $(this).attr("id", "one" + i);
      
      wordType = "";
      for (j = 0; j < firstSpan1.length; j ++) {
        if (i == firstSpan1[j]) {
          wordType = "selected";
        }
      }
      for (j = 0; j < index1.length; j ++) {
        if (i == index1[j]) {
          wordType = "seed";
        }
      }
      if (wordType == "seed" || wordType == "selected") {
        selectedIds1.push(i);
        allIds1.value = printArray(selectedIds1);
        selection1 = updateHighlightedWords(selectedIds1, "one");
        chooseFirstFactor.value = selection1;
        
        if (wordType == "seed") {
          hiddenFieldId1.value += i + " ";
          $(this).css( "background-color", colorMap["highlighted"] );
        }
        else {
          $(this).css( "background-color", colorMap["selected"] );
        }
      }
      
      startOffset += $(this).text().length;
      if ($(this).text() != "-" && $(this).text() != "/") {
        startOffset = startOffset + 1;
      }
    });
    
    words = $(".word_split2").text().split(" ");
    $(".word_split2").empty();
    $.each(words, function(i, v) {
      if (v.indexOf("-") <= 0 && v.indexOf("/") <= 0) {
        $(".word_split2").append(" ");
        $(".word_split2").append($("<span class=\"word\">").text(v.substring(0, v.length)));
      }
      else {
        if (v.indexOf("-") > 0) {
        words2 = v.split("-");
        $(".word_split2").append(" ");
        for (i = 0; i < words2.length - 1; i ++) {
          $(".word_split2").append($("<span class=\"word\">").text(words2[i]));
          $(".word_split2").append("-");
        }
        $(".word_split2").append($("<span class=\"word\">").text(words2[words2.length-1]));
      }
        if (v.indexOf("/") > 0) {
        words2 = v.split("/");
        $(".word_split2").append(" ");
        for (i = 0; i < words2.length - 1; i ++) {
          $(".word_split2").append($("<span class=\"word\">").text(words2[i]));
          $(".word_split2").append("/");
        }
        $(".word_split2").append($("<span class=\"word\">").text(words2[words2.length-1]));
      }
      }
    });
    
    var startOffset = 0;
    w = $(".word_split2").find("span").each(function() {
      if ($(this).text() == "-" || $(this).text() == "/") {
        startOffset = startOffset - 1;
      }
      var i = sentence2Text.indexOf($(this).text(), startOffset);
      $(this).attr("id", "two" + i);
      
      wordType = "";
      for (j = 0; j < firstSpan2.length; j ++) {
        if (i == firstSpan2[j]) {
          wordType = "selected";
        }
      }
      for (j = 0; j < index2.length; j ++) {
        if (i == index2[j]) {
          wordType = "seed";
        }
      }
      if (wordType == "seed" || wordType == "selected") {
        selectedIds2.push(i);
        allIds2.value = printArray(selectedIds2);
        selection2 = updateHighlightedWords(selectedIds2, "two");
        chooseSecondFactor.value = selection2;
        
        if (wordType == "seed") {
          hiddenFieldId2.value += i + " ";
          $(this).css( "background-color", colorMap["highlighted"] );
        }
        else {
          $(this).css( "background-color", colorMap["selected"] );
        }
      }
      
      startOffset += $(this).text().length;
      if ($(this).text() != "-" && $(this).text() != "/") {
        startOffset = startOffset + 1;
      }
    });   
  });
  
  sentence1[0].onclick = function( event ) {
    if(event.target.nodeName == "SPAN" && event.target.id.substring(0,3) == "one" &&
       event.target.style.backgroundColor != colorMap["highlighted"]) {
        if (selectedIds1.contains(event.target.id.slice(3))) {
7            //event.target.removeAttribute('style');
          selectedIds1.remove(event.target.id.slice(3));
          allIds1.value = printArray(selectedIds1);
          selection1 = updateHighlightedWords(selectedIds1, "one");
          chooseFirstFactor.value = selection1;
          event.target.removeAttribute('style');
          
          if (event.target.style.backgroundColor != colorMap["highlighted"]) {
            //event.target.style.backgroundColor = colorMap["highlighted"];
          }
         // alert(selection1);
        }
        else {
          selectedIds1.push(parseInt(event.target.id.slice(3)));
          allIds1.value = printArray(selectedIds1);
          selection1 = updateHighlightedWords(selectedIds1, "one");
          chooseFirstFactor.value = selection1;
          
          if (event.target.style.backgroundColor != colorMap["selected"]) {
            event.target.style.backgroundColor = colorMap["selected"];
          }
          /*else if (event.target.style.backgroundColor == colorMap["selected"]) {
            event.target.style.backgroundColor = colorMap["highlighted"];
          }*/
         // alert(selection1);
        }
   //   }
    }
  }
  
    sentence1[0].onmouseover = function( event ) {
      if(event.target.nodeName == "SPAN" && event.target.id.substring(0,3) == "one" &&
         event.target.style.backgroundColor != colorMap["highlighted"]) {
         event.target.style.backgroundColor = colorMap["hovered"];
        }
    }
         
    sentence1[0].onmouseout = function( event ) {
        if(event.target.nodeName == "SPAN" && event.target.id.substring(0,3) == "one" &&
           event.target.style.backgroundColor == colorMap["hovered"]) {
          if ( /* selectedConfirmIds1.contains(event.target.id.slice(3)) || */
              selectedIds1.contains(event.target.id.slice(3)) &&
              !index1.contains(event.target.id.slice(3)) ) {
             event.target.style.backgroundColor = colorMap["selected"];
          }
          else if (!index1.contains(event.target.id.slice(3))) {
            event.target.removeAttribute('style');
          }
        }
    }
    
  sentence2[0].onclick = function( event ) {
    if(event.target.nodeName == "SPAN" && event.target.id.substring(0,3) == "two" &&
       event.target.style.backgroundColor != colorMap["highlighted"]) {
      
     // if(radioText2[1].checked) {
        if (selectedIds2.contains(event.target.id.slice(3))) {
          if (event.target.style.backgroundColor != colorMap["highlighted"]) {
            // event.target.removeAttribute('style');
            // event.target.style.backgroundColor = colorMap["highlighted"];
          }  
          selectedIds2.remove(event.target.id.slice(3));
          allIds2.value = printArray(selectedIds2);
          selection2 = updateHighlightedWords(selectedIds2, "two");
          chooseSecondFactor.value = selection2;
          event.target.removeAttribute('style');
        }
        else{
          selectedIds2.push(parseInt(event.target.id.slice(3)));
          allIds2.value = printArray(selectedIds2);
          if (event.target.style.backgroundColor != colorMap["selected"]) {
            event.target.style.backgroundColor = colorMap["selected"];
          }
          selection2 = updateHighlightedWords(selectedIds2, "two");
          chooseSecondFactor.value = selection2;
        }
     // }
    }  
  }
    
    
  
    sentence2[0].onmouseover = function( event ) {
      if(event.target.nodeName == "SPAN" && event.target.id.substring(0,3) == "two" &&
         event.target.style.backgroundColor != colorMap["highlighted"]) {
         event.target.style.backgroundColor = colorMap["hovered"];
        }
    }
         
    sentence2[0].onmouseout = function( event ) {
        if(event.target.nodeName == "SPAN" && event.target.id.substring(0,3) == "two" &&
           event.target.style.backgroundColor == colorMap["hovered"]) {
          if ( /* selectedConfirmIds1.contains(event.target.id.slice(3)) || */
              selectedIds2.contains(event.target.id.slice(3)) &&
              !index2.contains(event.target.id.slice(3)) ) {
             event.target.style.backgroundColor = colorMap["selected"];
          }
          else if (!index2.contains(event.target.id.slice(3))) {
            event.target.removeAttribute('style');
          }
        }
    }
    
    function updateHighlightedWords(arrayId, indexSent) {
      arrayId.sort(function(a, b) {
        if (isNaN(a) || isNaN(b)) {
          if (a > b) return 1;
          else return -1;
        }
        return a - b;
      });
      var selection2 = "";
      for (var i = 0; i < arrayId.length; i ++) {
        var num = parseInt(arrayId[i]);
        var n = num.toString();
        if (indexSent == "one") {
          selection2 += document.getElementById("one" + n).innerHTML + " ";
        }
        if (indexSent == "two") {
          selection2 += document.getElementById("two" + n).innerHTML + " ";
        }
      }
      return selection2;
      }
  
  function printArray(array) {
    retValue = "";
    for (i = 0; i < array.length; i ++) {
      retValue += array[i] + "-";
    }
    if (array.length != 0) {
      retValue = retValue.slice(0, -1);
    }
    return retValue;
  }

</script>			
					<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->	
					</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
					
				</div>	
				<div class='col-xs-3 sidebar pull-right' style="height:660px; font-size:13px;">
				<div class="userinfo contentbox" style="margin-top:22px; margin-bottom:20px; width:320px; color:#333;">
						
							<div class="id_box"style="background:none;width:250px;height:100px;position:relative;left:20px;top:20px">
								<img src='BlankImage.png' style="width:90px;height:80px;position:relative;left:-30px;top:-10px;-moz-border-radius:9px; -webkit-border-radius:9px; border-radius:9px;"></img>
								<div class="player_info" style="width:170px;height:50px;position:relative;left:80px;top:-83px; font-size:14px;">
									<b>Dr. Wilson</b><br />Factorspan pro
									<hr style="margin-bottom:4px; margin-top:5px; border-top:1px solid grey" / >
									Reputation points:&nbsp;&nbsp;50&nbsp;<image src="medical_logo.png"></image>
								</div>
								<img src="progress_bar.png" style="width:110%; height:15px; position:relative;top:-50px; left:-27px;"></img>
							</div>
						<br />
						<i>&nbsp;&nbsp;&nbsp;&nbsp;Complete 3 more challenges to level up</i>
						<br />
					</div>
				
					<div class="startplaying" style="width:320px; margin-top:-20px;">
							<a href="game_Interview_mockup_contributing_home.html" style="color:black;"><button class='startButton'><b>Play other games</b><br /><span style="font-size:12px;">currently 10 people online</span></button></a>
					</div>
					<div class='col-xs-3 sidebar contentbox' style="height:500px; width:320px; color:#333; padding:30px; background:white; ">
					<center>
						<span style='font-size:17px;'><b><center>Beat the average score of the crowd to win the game!</center></b></span>
						<br />
						<span style='font-size:15px;'>The crowd: &nbsp;&nbsp;&nbsp;&nbsp;<span id="crowdScore">40</span></span>
						<br />
						<span style='font-size:15px;'>Your score:&nbsp;&nbsp;&nbsp;&nbsp;<span id="userScore">0</span></span>
						<br />
						<br />
						<span style='font-size:14px;'><b>Score breakdown</b></span>
						<span id="breakdownScore" style='overflow:auto; font-size:18px; background:none;'></span>
					</center>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>