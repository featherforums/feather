<?php namespace Feather\Components\Support;

use URL;
use HTML;
use Feather\Core;
use Feather\Components\Foundation\Component;

class Breadcrumbs extends Component {

	/**
	 * Crumbs to be dropped on the trail.
	 * 
	 * @var array
	 */
	public $crumbs = array();

	/**
	 * Drop a crumb.
	 * 
	 * @param  array|object  $crumb
	 * @return Feather\Components\Support\Breadcrumbs
	 */
	public function drop($crumb)
	{
		if($crumb instanceof Core\Place)
		{
			$this->place($crumb);
		}
		else
		{
			// If the crumb is an object, such as a Laravel Messages object, then convert it
			// to a string as any objects being passed to this method will have a __toString()
			if(is_object($crumb))
			{
				$crumb = array((string) $crumb);
			}
			elseif(!is_array($crumb))
			{
				$crumb = array($crumb);
			}

			// Fetch the title of the crumb and the crumbs link. If the crumb does not have
			// a supplied link then use the current page.
			$title = isset($crumb['title']) ? $crumb['title'] : array_shift($crumb);

			$link = isset($crumb['link']) ? $crumb['link'] : URL::current();

			$this->crumbs[] = (object) compact('link', 'title');
		}

		return $this;
	}

	/**
	 * Leaves a trail of crumbs.
	 * 
	 * @return string
	 */
	public function trail($element = 'li')
	{
		$response = array(
			$this->item(URL::to_route('feather'), $this->feather['config']->get('feather: db.forum.title'), $element)
		);

		foreach($this->crumbs as $crumb)
		{
			$response[] = $this->item($crumb->link, $crumb->title, $element);
		}

		return implode(PHP_EOL, $response);
	}

	/**
	 * Adds a place to the trail.
	 * 
	 * @param  Feather\Core\Place  $place
	 * @return array
	 */
	protected function place($place)
	{
		// Add each of the ancestors of this place to the crumbs.
		foreach($place->crumbs() as $crumb)
		{
			$this->crumbs[] = (object) array(
				'link'  => URL::to_route('place', array($crumb->id, $crumb->slug)),
				'title' => $crumb->name
			);
		}

		return $this->crumbs[] = (object) array(
			'link'  => URL::to_route('place', array($place->id, $place->slug)),
			'title' => $place->name
		);
	}

	/**
	 * Return a list item with the link.
	 * 
	 * @param  string  $link
	 * @param  string  $text
	 * @param  string  $element
	 * @return string
	 */
	public function item($link, $text, $element = 'li')
	{
		return "<{$element}>" . HTML::link($link, $text) . "</{$element}>";
	}

}