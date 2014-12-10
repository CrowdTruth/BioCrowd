<?php
/**
 * Judgement model. Judgements are saved in the judgements table. A judgement 
 * is a user's response to a single task, (e.g. UserX responds answers question 
 * posed by TaskA thus creating JudgementI.
 */
class Judgement extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'judgements';
}
