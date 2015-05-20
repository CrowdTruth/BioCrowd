<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This class implements the csvseed:games artisan command used for creating games from
 * a CSV file. This command takes a single parameter: the name of the CSV file in a special
 * format. Command can be called from command line:
 * 
 *   php artisan csvseed:games <csv_file>
 *   
 * where <csv_file> is a specially prepared CSV file. File 'samples/games.csv' is an example
 * of such file.
 */
class CSVGameSeeder extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'csvseed:games';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create games from a CSV file.';

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
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$filename =  $this->argument('filename');
		$this->info('Importing games from CSV: '.$filename);

		$controller = new GameAdminController;
		$result = $controller->parseGameFile($filename);

		if($result['status']=='Success') {
			$this->info('Import finished successfully!');
		} else {
			$this->error('Failed to import data');
			$this->error('  > '.$result['message']);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			[ 'filename', InputArgument::REQUIRED, 'Input game CSV file.' ]
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}
}
