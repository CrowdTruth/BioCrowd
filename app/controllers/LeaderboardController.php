<?php
/**
 * This controller controls what the leaderboard shows.
 */
class LeaderboardController extends BaseController {
	
	/**
	 * Returns the view "leaderboard"
	 */
	public function getView() {
		//Set the setting to determine what will be rated
		$setting = 'scores';
		//Make the standard view with the top 20
		$rows = DB::table('users')
			->join('ranks', 'users.id', '=', 'ranks.user_id')
			->select(['user_id','name','level','score','currentRank'])
			->orderBy('currentRank')
			->take(20)
			->get();
		$userRank = null;
		//if the user is logged in and the user is in the ranks table, set the userRank
		if(Auth::user()->check() && Rank::where('user_id',Auth::user()->get()->id)->select('currentRank')->first()){
			$userRank = Rank::where('user_id',Auth::user()->get()->id)->select('currentRank')->first()->currentRank;
		}
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "scoresday"
	 */
	public function top20Today() {
		//Set the setting to determine what will be rated
		$setting = 'scores';
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the scores from just today for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')
				->groupBy('user_id')
				->get()->toArray() )
					->groupBy('user_id')
					->selectRaw('user_id,name,level,sum(score_gained) as score')
					->whereDate('scores.updated_at','=',$carbon->now()->toDateString())
					->orderBy('score','desc')
					->leftJoin('users', 'scores.user_id', '=', 'users.id')
					->get()->toArray();
		$userRank = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null. 
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank variable
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
		
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "scoresweek"
	 */
	public function top20Week() {
		//Set the setting to determine what will be rated
		$setting = 'scores';
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the scores from the last 7 days for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')
				->groupBy('user_id')
				->get()->toArray() )
					->groupBy('user_id')
					->selectRaw('user_id,name,level,sum(score_gained) as score')
					->whereBetween('scores.updated_at',array($carbon->now()->subWeek()->addDay()->toDateString(),$carbon->now()->addDay()->toDateString()))
					->orderBy('score','desc')
					->leftJoin('users', 'scores.user_id', '=', 'users.id')
					->get()->toArray();
		$userRank = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank variable
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "scoresmonth"
	 */
	public function top20Month() {
		//Set the setting to determine what will be rated
		$setting = 'scores';
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the scores from the last month for every user and put them in an array
		$rows = Score::whereIn('user_id' , Score::select('user_id')
				->groupBy('user_id')
				->get()->toArray() )
					->groupBy('user_id')
					->selectRaw('user_id,name,level,sum(score_gained) as score')
					->whereBetween('scores.updated_at',array($carbon->now()->subMonth()->addDay()->toDateString(),$carbon->now()->addDay()->toDateString()))
					->orderBy('score','desc')
					->leftJoin('users', 'scores.user_id', '=', 'users.id')
					->get()->toArray();
		$userRank = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank variable
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "20judge"
	 */
	public function top20Judge() {
		//Set the setting to determine what will be rated
		$setting = 'judgements';
		//Calculate the amount of finished judgements for every user and put them in an array
		$rows = Judgement::select(DB::raw('user_id,name,level,COUNT(X.user_id) as nJudgements'))
							->from(DB::raw('(SELECT distinct user_id,task_id,updated_at from judgements WHERE flag = \'\') as X'))
							->groupBy('user_id')
							->orderBy('nJudgements','desc')
							->leftJoin('users', 'X.user_id', '=', 'users.id')
							->get()->toArray();
		
		$userRank = null;
		$userNJudgements = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank and $userNJudgements variables
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
					$userNJudgements = $rows[$i]['nJudgements'];
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('userNJudgements',$userNJudgements)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "judgeday"
	 */
	public function top20JudgeDay() {
		//Set the setting to determine what will be rated
		$setting = 'judgements';
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the amount of finished judgements for every user and put them in an array
		$rows = Judgement::select(DB::raw('user_id,name,level,COUNT(X.user_id) as nJudgements'))
							->from(DB::raw('(SELECT distinct user_id,task_id,updated_at from judgements WHERE flag = \'\' AND (date(updated_at) = \''.$carbon->now()->toDateString().'\')) as X'))
							->groupBy('user_id')
							->orderBy('nJudgements','desc')
							->leftJoin('users', 'X.user_id', '=', 'users.id')
							->get()->toArray();
	
		$userRank = null;
		$userNJudgements = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank and $userNJudgements variables
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
					$userNJudgements = $rows[$i]['nJudgements'];
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('userNJudgements',$userNJudgements)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "judgeweek"
	 */
	public function top20JudgeWeek() {
		//Set the setting to determine what will be rated
		$setting = 'judgements';
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the amount of finished judgements for every user and put them in an array
		$rows = Judgement::select(DB::raw('user_id,name,level,COUNT(X.user_id) as nJudgements'))
		->from(DB::raw('(SELECT distinct user_id,task_id,updated_at from judgements WHERE flag = \'\' AND (date(updated_at) BETWEEN (\''.$carbon->now()->subWeek()->addDay()->toDateString().'\') AND (\''.$carbon->now()->addDay()->toDateString().'\'))) as X'))
		->groupBy('user_id')
		->orderBy('nJudgements','desc')
		->leftJoin('users', 'X.user_id', '=', 'users.id')
		->get()->toArray();
	
		$userRank = null;
		$userNJudgements = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank and $userNJudgements variables
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
					$userNJudgements = $rows[$i]['nJudgements'];
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('userNJudgements',$userNJudgements)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "judgemonth"
	 */
	public function top20JudgeMonth() {
		//Set the setting to determine what will be rated
		$setting = 'judgements';
		//get the Carbon model
		$carbon = App::make('Carbon\Carbon');
		//Calculate the amount of finished judgements for every user and put them in an array
		$rows = Judgement::select(DB::raw('user_id,name,level,COUNT(X.user_id) as nJudgements'))
		->from(DB::raw('(SELECT distinct user_id,task_id,updated_at from judgements WHERE flag = \'\' AND (date(updated_at) BETWEEN (\''.$carbon->now()->subMonth()->addDay()->toDateString().'\') AND (\''.$carbon->now()->addDay()->toDateString().'\'))) as X'))
		->groupBy('user_id')
		->orderBy('nJudgements','desc')
		->leftJoin('users', 'X.user_id', '=', 'users.id')
		->get()->toArray();
	
		$userRank = null;
		$userNJudgements = null;
		if($rows){
			//if the $rows array is full of scores, make $rows null.
			$i=0;
			//go over all rows to add the ranks in the array
			foreach($rows as $todayScore){
				$rank = $i+1;
				$rows[$i]['currentRank']=$rank;
				//If the user is logged in and the user id is equal to the id of the user that is now logged in, fill the $userRank and $userNJudgements variables
				if((Auth::user()->check()) && ($rows[$i]['user_id'] == Auth::user()->get()->id)){
					$userRank = $rank;
					$userNJudgements = $rows[$i]['nJudgements'];
				}
				$i++;
			}
			//Take the first 20 entries of the list
			$rows = array_slice($rows, 0, 20);
		}
	
		//Make the standard view with the top 20 scores of today
		return View::make('leaderboard')->with('rows', $rows)->with('userRank',$userRank)->with('userNJudgements',$userNJudgements)->with('setting',$setting);
	}
	
	/**
	 * Returns the view "sidebarLeaderboard"
	 */
	public function sidebarLeaderboard() {
		//Make the standard view with the top 20
		$rows = DB::table('users')
		->join('ranks', 'users.id', '=', 'ranks.user_id')
		->select(['user_id','name','level','score','currentRank'])
		->orderBy('currentRank')
		->take(5)
		->get();
		$userRank = null;
		//if the user is logged in and the user is in the ranks table, set the userRank
		if(Auth::user()->check() && Rank::where('user_id',Auth::user()->get()->id)->select('currentRank')->first()){
			$userRank = Rank::where('user_id',Auth::user()->get()->id)->select('currentRank')->first()->currentRank;
		}
		return [$rows,$userRank];
	}
}