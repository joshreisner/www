<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class import extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$importer = new ImportController;
		$importer->getLastFm();
		$importer->getInstagram();
		$importer->getReadability();
		$importer->getTwitter();
		$importer->getVimeo();
		$importer->getFoursquare();
		$importer->getGoodreads();
		print "done\n";
	}

}
