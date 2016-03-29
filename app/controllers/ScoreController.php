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
		//First, create a new entry in the "scores" table in the database
		$score = new Score();
		$score->user_id = $userId;
		$score->game_id = $gameId;
		$score->campaign_id = $campaignId;
		$score->score_gained = $scoreGained;
		$score->description = $description;
		$score->save();
		
		//Then, calculate the score for this user and update it to this user's score column in the database
		$user = User::find($userId);
		$newUserScore = Score::where('user_id',$userId)->sum('score_gained');
		$user->score = $newUserScore;
		$user->save();
		
		//update the ranks table
		ScoreController::calculateRanks();
		
		//update the user's level and title
		ScoreController::updateUserLevelAndTitle($userId);
	}
	
	static public function calculateRanks() {
		$allUsersOrderedOnScore = User::orderBy('score','desc')->get();
		$i=1;
		foreach($allUsersOrderedOnScore as $user){
			//check if a user is already in the rank table
			if(Rank::where('user_id',$user->id)->count() >= 1){
				//if the user already exists, only edit this ranking. 
				$rank = Rank::where('user_id',$user->id)->first();
				$rank->currentRank = $i;
				$rank->save();
			} else {
				//if the user isn't in the ranking table yet, add a new rank. 
				$rank = new Rank;
				$rank->user_id = $user->id;
				$rank->currentRank = $i;
				$rank->save();
			}
			$i++;
		}
	}
	
	static public function updatePreviousRanks() {
		$allUsersOrderedOnScore = User::orderBy('score','desc')->get();
		$i=1;
		foreach($allUsersOrderedOnScore as $user){
			//check if a user is already in the rank table
			if(Rank::where('user_id',$user->id)->count() >= 1){
				//if the user already exists, only edit this ranking.
				$rank = Rank::where('user_id',$user->id)->first();
				$rank->previousRank = $i;
				$rank->save();
			} else {
				//if the user isn't in the ranking table yet, add a new rank.
				$rank = new Rank;
				$rank->user_id = $user->id;
				$rank->previousRank = $i;
				$rank->save();
			}
			$i++;
		}
	}
	
	static public function updateUserLevelAndTitle($userId) {
		$user = User::find($userId);
		//compare the user score to the level table and get the highest level entry in the table that still applies to the user
		$levelEntryForUser = Level::where('max_score','<=',$user->score)->orderBy('level','desc')->first();
		if($levelEntryForUser) {
			//set the user level and title according to the retrieved level entry for this user
			$user->level = $levelEntryForUser->level;
			$user->title = $levelEntryForUser->title;
			$user->save();
		}
	}
}