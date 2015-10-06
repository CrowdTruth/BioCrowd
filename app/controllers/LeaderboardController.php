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
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the scores from just today for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')->groupBy('user_id')->get()->toArray() )->groupBy('user_id')->selectRaw('user_id,name,level,sum(score_gained) as score')->whereDate('scores.updated_at','=',$carbon->now()->toDateString())->orderBy('score','desc')->leftJoin('users', 'scores.user_id', '=', 'users.id')->get()->toArray();
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
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the scores from the last 7 days for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')->groupBy('user_id')->get()->toArray() )->groupBy('user_id')->selectRaw('user_id,name,level,sum(score_gained) as score')->whereBetween('scores.updated_at',array($carbon->now()->subWeek()->addDay()->toDateString(),$carbon->now()->addDay()->toDateString()))->orderBy('score','desc')->leftJoin('users', 'scores.user_id', '=', 'users.id')->get()->toArray();
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
	
	public function top20Month() {
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the scores from the last month for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')->groupBy('user_id')->get()->toArray() )->groupBy('user_id')->selectRaw('user_id,name,level,sum(score_gained) as score')->whereBetween('scores.updated_at',array($carbon->now()->subMonth()->addDay()->toDateString(),$carbon->now()->addDay()->toDateString()))->orderBy('score','desc')->leftJoin('users', 'scores.user_id', '=', 'users.id')->get()->toArray();
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
	
	public function top20Judge() {
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the amount of finished judgements for every user and put them in an array
		$rows = Judgement::where('flag','')->whereIn('user_id' , Score::select('user_id')->groupBy('user_id')->get()->toArray() )->groupBy('user_id')->selectRaw('user_id,name,level,count(judgements.id) as nJudgements')->orderBy('nJudgements','desc')->leftJoin('users', 'judgements.user_id', '=', 'users.id')->get()->toArray();
		//TODO: don't count one task id as multiple task id's
		//TODO: figure out why user 22 is not added to the users table
		dd($rows);
		
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