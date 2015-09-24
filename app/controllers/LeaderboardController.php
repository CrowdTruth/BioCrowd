<?php
/**
 * This controller controls what the leaderboard shows.
 */
class LeaderboardController extends BaseController {
	
	/**
	 * Returns the view "profile"
	 */
	public function getView() {
		//Make the standard view with the top 20
		//$rows = User::select(['name','level','score'])->orderBy('score','desc')->take(20)->get();
		$rows = User::select(['name','level','score',DB::raw('FIND_IN_SET( score, (
				SELECT GROUP_CONCAT( score
				ORDER BY score DESC ) 
				FROM users )
				)')])->as('rank')
		->take(20)->get()->toArray();
		Log::error($rows);
		return View::make('leaderboard')->with('rows', $rows);
	}
	
}