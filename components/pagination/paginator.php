<?php namespace Feather\Components\Support;

use URI;
use HTML;
use Request;

class Paginator extends \Laravel\Paginator {

	/**
	 * Get the current page from the last segment of the URI.
	 *
	 * @param  int  $total
	 * @param  int  $per_page
	 * @return int
	 */
	public static function page($total, $per_page)
	{
		if(!is_numeric($page = substr(URI::$segments[count(URI::$segments) - 1], 1)))
		{
			$page = 1;
		}
		
		// The page will be validated and adjusted if it is less than one or greater
		// than the last page. For example, if the current page is not an integer or
		// less than one, one will be returned. If the current page is greater than
		// the last page, the last page will be returned.
		if (is_numeric($page) and $page > $last = ceil($total / $per_page))
		{
			return ($last > 0) ? $last : 1;
		}

		return (static::valid($page)) ? $page : 1;
	}

	/**
	 * Create a HTML page link.
	 *
	 * @param  int     $page
	 * @param  string  $text
	 * @param  string  $class
	 * @return string
	 */
	protected function link($page, $text, $class)
	{
		$page = "p{$page}";

		if(preg_match('/p([0-9]+)/', $page))
		{
			$uri = preg_replace('/\/p([0-9]+)/', '', URI::current(), 1);
		}

		return HTML::link($uri . '/' . $page, $text, compact('class'), Request::secure());
	}

}