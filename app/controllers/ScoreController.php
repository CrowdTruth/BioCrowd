<?php
/**
 * This controller controls adding scores to the users and scores tables. 
 */
class ScoreController {
	/**
	 * Add given score to the given userId from the given game and possibly campaign to the users and scores tables.
	 * 
	 * @param $score The score that will be added
	 * @param $userId The id of the user that will receive the score
	 * @param $description A discription of the way the score was obtained
	 * @param $gameId The id of the Game the user was playing when the score was added, defaults to null
	 * @param $campaignId The id of the Campaign for which to add the score, defaults to null
	 */
	static public function addScore($scoreGained, $userId, $description, $gameId = null, $campaignId = null) {
		//first, add the score to this user's score column in the database
		$user = User::find($userId);
		$oldUserScore = $user->score;
		$user->score = ($scoreGained + $oldUserScore);
		$user->save();
		//Then, create a new entry in the "scores" table in the database
		$score = new Score();
		$score->user_id = $userId;
		$score->game_id = $gameId;
		$score->campaign_id = $campaignId;
		$score->score_gained = $scoreGained;
		$score->description = $description;
		$score->save();
		
		//check the level of the user and see if it needs to be higher
		//what is the max score for the level of this user
		$maxScoreForThisLevel = Level::where('level',$user->level)->first(['max_score'])['max_score'];
		if($user->score > $maxScoreForThisLevel){
			//if it does need to be higher, up the user's level
			$user->level = $user->level+1;
			$user->title = Level::where('level',$user->level)->first(['title'])['title'];
			$user->save();
		}
	}
}