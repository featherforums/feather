<?php namespace Feather\Extensions;

interface ExtensionInterface {

	/**
	 * Executed when an extension is started.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function start($app);

	/**
	 * Executed when an extension is installed.
	 * 
	 * @return void
	 */
	public function installed();

	/**
	 * Executed when an extension is activated.
	 * 
	 * @return void
	 */
	public function activated();

	/**
	 * Executed when an extension is deactivated.
	 * 
	 * @return void
	 */
	public function deactivated();

	/**
	 * Executed when an extension is removed.
	 * 
	 * @return void
	 */
	public function removed();

}