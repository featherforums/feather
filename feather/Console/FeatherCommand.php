<?php namespace Feather\Console;

use Illuminate\Console\Command;

class FeatherCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'feather';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get Feather version information';

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