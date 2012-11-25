<?php namespace Feather\Console;

use RuntimeException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Foundation\AssetPublisher as Publisher;

class PublishCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'feather:publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish extensions or themes';

	/**
	 * Asset publisher instance.
	 * 
	 * @var Illuminate\Foundation\AssetPublisher
	 */
	protected $publisher;

	/**
	 * Path to themes
	 * 
	 * @var string
	 */
	protected $themePath;

	/**
	 * Path to extensions.
	 * 
	 * @var string
	 */
	protected $extensionPath;

	/**
	 * Create a new publish command instance.
	 * 
	 * @param  Illuminate\Foundation\AssetPublisher  $publisher
	 * @param  string  $themePath
	 * @param  string  $extensionPath
	 * @return void
	 */
	public function __construct(Publisher $publisher, $themePath, $extensionPath)
	{
		parent::__construct();

		$this->publisher = $publisher;
		$this->themePath = $themePath;
		$this->extensionPath = $extensionPath;
	}

	/**
	 * Execute the console command.
	 * 
	 * @return void
	 */
	public function fire()
	{
		// The publish command encompasses both themes and extensions. Because of this a flag must be provided so that the
		// publisher knows what's being published and where it is located.
		if ( ! $this->input->getOption('theme') and ! $this->input->getOption('extension'))
		{
			$this->comment("Please use either the --theme or --extension flag.");

			return;
		}

		$name = $this->input->getArgument('name');

		// Using the AssetPublisher we can publish our themes or extensions to the correct destination within our public
		// directory. If publishing fails we'll catch the exception and let the user no that they probably spelt the
		// theme or extension incorrectly.
		
		if ($this->input->getOption('theme'))
		{
			try
			{
				$this->publisher->publish("{$this->themePath}/{$name}/public", "../feather/themes/{$name}");

				$this->line("<info>Successfully published theme:</info> {$name}");
			}
			catch (RuntimeException $error)
			{
				$this->comment('Failed to publish theme. There may be nothing to publish.');
			}
		}
		elseif ($this->input->getOption('extension'))
		{
			try
			{
				$this->publisher->publish("{$this->extensionPath}/{$name}/public", "../feather/extensions/{$name}");

				$this->line("<info>Successfully published extension:</info> {$name}");
			}
			catch (RuntimeException $error)
			{
				$this->comment('Failed to publish extension. There may be nothing to publish.');
			}
		}
	}

	/**
	 * Get the command arguments.
	 * 
	 * @return array
	 */
	public function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the theme or extension to publish')
		);
	}

	/**
	 * Get the command options.
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return array(
			array('theme', null, InputOption::VALUE_NONE, 'Tells publisher to publish a theme'),
			array('extension', null, InputOption::VALUE_NONE, 'Tells publisher to publish an extension'),
		);
	}

}