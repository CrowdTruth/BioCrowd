<?php
/**
 * This CampaignTypeHandler does nothing -- this class is mean as an example to illustrate 
 * how to develop your own CampaignTypeHandler.
 */
class DummyCampaignType extends CampaignTypeHandler {
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getName() {
		return 'Dummy';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getDescription() {
		return 'Not a real campaign, just for devel debugging and stuff...';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		return 'No DIV here, sorry :-P';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		return 'Should return data ;-)';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getThumbnail() {
		return 'img/factor_validation1.png';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function getView($campaign) {
		return View::make('dummycampaign')
			->with('instructions', $campaign->instructions)
			->with('campaignId', $campaign->id);
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function processResponse($campaign) {
		return 'We should do something with your answer...<a href="/gameMenu">home</a>';
	}
	
	/**
	 * See CampaignTypeHandler
	 */
	public function renderGame($game) {
		return $game->data;
	}
	
	/**
	 * See GameTypeHandler
	 */
	public function validateData($data) {
		return true;
	}
}
