<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Call CrowdTruth webhook. Command can be called from command line:
 * 
 *   php artisan ctWebhook:call
 * 
 * Command can also be called as a scheduled task:
 * 
 *   php artisan scheduled:run
 * 
 * NOTE: php artisan scheduled:run must be added to crontab to run all scheduled tasks.
 * See https://github.com/Indatus/dispatcher/tree/1.4#Cron for more info.
 */
class CTwebhook extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ctWebhook:call';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Call CrowdTruth webhook.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * When a command should run
	 *
	 * @param Scheduler $scheduler
	 * @return \Indatus\Dispatcher\Scheduling\Schedulable
	 */
	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->everyMinutes(10);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$response = DataportController::callWebhook();
		
		if($response['status']=='ok') {
			$this->info('Webhook successfully called');
			$this->info('  > '.$response['message']);
		} else {
			$this->error('Error during webhook call');
			$this->error('  URL > '.$response['URL']);
			$this->error('  N   > '.count($response['query']['payload']));
			$this->error('  msg > '.$response['message']);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
