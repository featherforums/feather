<?php namespace Feather\Components\Foundation;

class Component {

	/**
	 * The feather instance.
	 * 
	 * @var Feather\Components\Foundation\Application
	 */
	protected $feather;

	/**
	 * Create a new component instance.
	 * 
	 * @param  Feather\Components\Foundation\Application  $feather
	 * @return void
	 */
	public function __construct(Application $feather)
	{
		$this->feather = $feather;
	}

}