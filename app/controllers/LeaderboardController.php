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
		$rows = DB::table('users')->join('ranks', 'users.id', '=', 'ranks.user_id')->select(['name','level','score','currentRank'])->orderBy('score','desc')->take(20)->get();
						
		return View::make('leaderboard')->with('rows', $rows);
	}
	
}