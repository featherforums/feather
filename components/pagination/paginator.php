<?php namespace Feather\Components\Pagination;

use URI;
use HTML;
use Request;
use Feather\Components\Foundation\Component;

class Paginator extends \Laravel\Paginator {

	/**
	 * Overload the constructor so that we can get instances of it with the facades.
	 * 
	 * @param  array  $results
	 * @param  int    $page
	 * @param  int    $total
	 * @param  int    $per_page
	 * @param  int    $last
	 * @return void
	 */
	public function __construct($results = null, $page = null, $total = null, $per_page = null, $last = null)
	{
		$this->page = $page;
		$this->last = $last;
		$this->total = $total;
		$this->results = $results;
		$this->per_page = $per_page;
	}

	/**
	 * Create a new Paginator instance.
	 *
	 * @param  array      $results
	 * @param  int        $total
	 * @param  int        $per_page
	 * @return Paginator
	 */
	public static function make($results, $total, $per_page)
	{
		$page = static::page($total, $per_page);

		$last = ceil($total / $per_page);

		return new static($results, $page, $total, $per_page, $last);
	}

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
		if (is_numeric($page) and $page > ($last = ceil($total / $per_page)))
		{
			return ($last > 0) ? $last : 1;
		}

		return $page;
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