@extends('layout')

@section('extraheaders')
	<link href="css_final/CSS.css" rel="stylesheet">
	<script src="js_final/require.js"></script>
	<script src="js_final/JavaScript.js"></script>
	<script src="/js_final/jquery.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
		<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->
		<section class="container" style="padding: 10px 10px; font-family: Verdana, Geneva, sans-serif; color: #333333; font-size: 0.9em;">
			<div class="row col-md-12" style="width: 76%;">
				<!-- Instructions -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<strong>Instructions</strong>
					</div>

					<div class="panel-body">
						{{ $instructions }}
						<p>
							Examples of cells:
						</p>
						<img src="img/110803_a1b_ch00.png" width="200">
						<img src="img/110803_a2a_ch00.png" width="200">
						<img src="img/110803_a2b_ch00.png" width="200">
					</div>
				</div>
				<!-- End Instructions -->
				
				<div class="panel panel-primary">
					<div class="panel-body">
						<form>
							<input type="hidden" name="totaltime">
							<input type="hidden" name="tagtiming">
							<input type="hidden" name="starttime">
							<input type="hidden" name="endtime">
							<input type="hidden" name="annotations">
							<input type="hidden" name="fullsizeimageclicked" value="false">
							<input type="hidden" name="contributors_browser" validates="user_agent">
						</form>

						<div class="span7">
							<a href="img/110803_a1_ch00.png" class="fullsizeimage" target="_blank" title="Click to open a full-size version in a new tab">Click for the full-size image</a><BR>
							<img class="annotatable" id="annotatableImage" src="{{ $image }}" />
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
									Number of bounding boxes: <span class="nrTags" id="nrTags">0</span>
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
				
							{{ Form::open(array('url' => 'submitGame')) }}
							{{ Form::hidden('gameId', $gameId) }}
							{{ Form::hidden('taskId', $taskId) }}
							{{ Form::hidden('response','', [ 'id' => 'response' ] ) }}
							<table width="100%">
							<tr><td align="center">{{ Form::submit('Submit') }}</td></tr>
							</table>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->
		</section>
	</div>
</div>
@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop
