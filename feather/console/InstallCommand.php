<?php namespace Feather\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'feather:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Installs Feather in current directory';

	/**
	 * Execute the console command.
	 * 
	 * @return void
	 */
	public function fire()
	{
		$this->line('<info>Feather</info> version <comment>'.FEATHER_VERSION.'</comment>');
	}
}