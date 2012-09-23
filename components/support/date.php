<?php namespace Feather\Components\Support;

use DateTime;
use Feather\Components\Foundation\Component;

class Date extends Component {

	/**
	 * Array of the fuzzy date periods and lengths.
	 * 
	 * @var array
	 */
	protected $fuzzy = array(
		'periods' => array('second', 'minute', 'hour', 'day', 'week', 'month', 'year'),
		'lengths' => array(60, 60, 24, 7, 4.35, 12)
	);

	/**
	 * Alternate text to show if date is invalid.
	 * 
	 * @var string
	 */
	public $alternate;

	/**
	 * Text to show before the returned formatted string.
	 * 
	 * @var string
	 */
	public $prefix;

	/**
	 * Text to show after the returned formatted string.
	 * 
	 * @var string
	 */
	public $suffix;

	/**
	 * The DateTime being used for the current instance.
	 * 
	 * @var object
	 */
	public $date;

	/**
	 * Set the date to use.
	 * 
	 * @param  int|string  $date
	 * @return Feather\Components\Support\Date
	 */
	public function set($date)
	{
		$this->date = new DateTime;

		if(is_numeric($date))
		{
			$this->date->setTimestamp($date);
		}
		else
		{
			$this->date->setTimestamp(strtotime($date));
		}

		return $this;
	}

	/**
	 * Format a date and return it for the discussion meta data.
	 * 
	 * @param  string|int  $date
	 * @return string
	 */
	public function meta($date)
	{
		$this->set($date);

		return '<span title="' . $this->show('long') . '">' . $this->relative('short') . '</span>';
	}

	/**
	 * Sets the alternate text to display if the date is of an invalid format.
	 * 
	 * @param  string  $text
	 * @return Feather\Components\Support\Date
	 */
	public function alternate($text)
	{
		$this->alternate = $text;

		return $this;
	}

	/**
	 * Sets the prefix text.
	 * 
	 * @param  string  $text
	 * @return Feather\Components\Support\Date
	 */
	public function prefix($text)
	{
		$this->prefix = $text;

		return $this;
	}

	/**
	 * Sets the suffix text.
	 * 
	 * @param  string  $text
	 * @return Feather\Components\Support\Date
	 */
	public function suffix($text)
	{
		$this->suffix = $text;

		return $this;
	}
	
	/**
	 * Returns the formatted date.
	 * 
	 * @param  string  $format
	 * @return string
	 */
	public function show($format)
	{
		$formats = array(
			'long'  => $this->feather['config']->get('feather: db.datetime.long_date'),
			'short' => $this->feather['config']->get('feather: db.datetime.short_date'),
			'time'  => $this->feather['config']->get('feather: db.datetime.time_only')
		);

		if(isset($formats[$format]))
		{
			$format = $formats[$format];
		}

		$errors = $this->date->getLastErrors();

		if($this->date->getTimestamp() == 0 or $errors['warning_count'] > 0 or $errors['error_count'] > 0)
		{
			return $this->alternate ?: 'Invalid date supplied.';
		}

		return $this->prefix . $this->date->format($format) . $this->suffix;
	}
	
	/**
	 * Returns a fuzzy formated date.
	 * 
	 * @return string
	 */
	public function fuzzy($stop = 'week', $format = 'long')
	{
		$difference = time() - $this->date->getTimestamp();

		$offset = array_search($stop, $this->fuzzy['periods']) + 1;

		$periods = array_slice($this->fuzzy['periods'], 0, $offset);

		$lengths = array_slice($this->fuzzy['lengths'], 0, $offset);
		
		for($i = 0; $difference >= $lengths[$i] and $i < count($lengths) - 1; $i++)
		{
			$difference = $difference / $lengths[$i];
		}
		
		$difference = round($difference);
		
		if($difference != 1)
		{
			$periods[$i] .= 's';
		}

		if($difference > $lengths[$i])
		{
			return $this->show($format);
		}

		return $this->prefix . number_format($difference) . ' ' . $periods[$i] . ' ago' . $this->suffix;
	}

	/**
	 * Returns a relative formated date.
	 * 
	 * @return string
	 */
	public function relative($format = 'long')
	{
		$days = intval((time() - $this->date->getTimestamp()) / 86400);

		if($days == 0)
		{
			return __('feather::common.today')->get() . ', ' . $this->show('time');
		}
		elseif($days == 1)
		{
			return __('feather::common.yesterday')->get() . ', ' . $this->show('time');
		}

		return $this->show($format);
	}
	
}