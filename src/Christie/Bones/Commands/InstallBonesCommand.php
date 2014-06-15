<?php

namespace Christie\Bones\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \Config;

use \Christie\Bones\Models\Site;
use \Christie\Bones\Models\User;
use \Christie\Bones\Libraries\Bones;

class InstallBonesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bones:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install and configure Bones CMS.';

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
		/*
		 *  Run all bones migrations
		 */
		$this->info('Running package migrations...');
		Command::call('migrate', array('--package' => 'christie/bones'));
		$this->info("Migrations complete.\n");

		/*
		 *  Create site if one doesn't exist
		 */
		if (Site::count() == 0) {
			$this->info('We need to configure the first website, we need a URL, title, and slug (which is used for site-specific folder directories).');
			$site 			= new Site;
			$site->url 		= $this->ask('Enter site URL http://');
			$site->title 	= $this->ask('Enter site title: ');
			$site->slug 	= $this->ask('Enter site slug: ');
			$site->save();

			$this->info("Site created!\n");
		} else {
			$this->info('Site already configured.');
		}

		/*
		 *  Create a user if one doesn't exist
		 */
		if (User::count() == 0) {
			$this->info('We need to create the first super-user, this user will have complete CMS access.');
			$user 				= new User;
			$user->username		= $this->ask('Username: ');
			$user->display_name = $this->ask('Display name: ');
			$user->email 		= $this->ask('Email address: ');
			$user->password 	= \Hash::make( $this->secret('Password: ') );
			$user->site_id 		= Site::first()->id;
			$user->level		= Bones::LEVEL_SUPER;
			$user->save();

			$this->info("User created!\n");
		} else {
			$this->info('User already exists.');
		}

		/*
		 *  Copy package assets
		 */
		if ($this->confirm('Bones admin area requires various JS and CSS assets be copied to the public directory, would you like to do this now?')) {
			Command::call('asset:publish');
		}
		$this->info(PHP_EOL);

		/*
		 *  Copy view files
		 */
		if ($this->confirm('Bones allows you to override any view file with your own from within the views directory, would you like to copy all the view files for you to modify?')) {
			Command::call('view:publish', array('package' => 'christie/bones'));
		}
		$this->info(PHP_EOL);

		/*
		 *  Ensure the change the auth model
		 */
		if (Config::get('auth.model') != 'Christie\Bones\Models\User') {
			do {
				$this->info('You must change the auth.model config to "Christie\Bones\Models\User" to continue.');
				$this->confirm('Have you changed it?');
			} while(Config::get('auth.model') != 'Christie\Bones\Models\User');
		}

		/*
		 *  Done!
		 */
		$this->info('Done!');
		$this->info('Head to http://'.Site::first()->url.'/admin to get started.');


	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(

		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(

		);
	}

}
