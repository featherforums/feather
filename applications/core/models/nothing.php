<?php namespace Feather\Core;

class Nothing {

	/**
	 * Nothing only needs to return no results, because a there is nothing in
	 * this relationship.
	 * 
	 * @return array
	 */
	public function results()
	{
		return array();
	}

}