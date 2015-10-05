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
		$rows = DB::table('users')->join('ranks', 'users.id', '=', 'ranks.user_id')->select(['user_id','name','level','score','currentRank'])->orderBy('currentRank')->take(20)->get();
		
		if(Auth::user()->check()){
			return View::make('leaderboard')->with('rows', $rows)->with('userRank',Rank::where('user_id',Auth::user()->get()->id)->select('currentRank')->first()->currentRank);
		} else {
			return View::make('leaderboard')->with('rows', $rows);
		}
	}
	
	public function top20Today() {
		//Calculate the scores from just today for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')->groupBy('user_id')->get()->toArray() )->groupBy('user_id')->selectRaw('user_id,name,level,sum(score_gained) as score')->whereDate('scores.updated_at','=',date("Y-m-d"))->orderBy('score','desc')->leftJoin('users', 'scores.user_id', '=', 'users.id')->get()->toArray();
		$userRank = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null. 
			$i=0;
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
		
		//Make the standard view with the top 20 scores of today
		if(Auth::user()->check()){
			return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank);
		} else {
			return View::make('leaderboard')->with('rows', $rows);
		}
	}
	
	public function top20Week() {
		//Calculate the scores from just today for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')->groupBy('user_id')->get()->toArray() )->groupBy('user_id')->selectRaw('user_id,name,level,sum(score_gained) as score')->whereBetween('scores.updated_at',array(date("Y-m-d"),strtotime("+1 week -1 day")))->orderBy('score','desc')->leftJoin('users', 'scores.user_id', '=', 'users.id')->get()->toArray();
		$userRank = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		if(Auth::user()->check()){
			return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank);
		} else {
			return View::make('leaderboard')->with('rows', $rows);
		}
	}
	
}