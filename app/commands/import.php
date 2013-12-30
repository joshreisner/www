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
		echo 'Running importer for ';
		$importer = new ImportController;
		$importer->getLastFm();
		print 'LastFm, ';
		$importer->getInstagram();
		print 'Instagram, ';
		$importer->getReadability();
		print 'Readability, ';
		$importer->getTwitter();
		print 'Twitter, ';
		$importer->getVimeo();
		print 'Vimeo, ';
		$importer->getFoursquare();
		print 'Foursquare, ';
		$importer->getGoodreads();
		print 'Goodreads';

		print '... done.' . "\n";
	}

}
