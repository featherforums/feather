<?php

class Feather_Publish_Task {

	/**
	 * Register all the gear and themes and publish all their assets.
	 * 
	 * @return void
	 */
	public function run()
	{
		$this->theme() and $this->gear();

		echo PHP_EOL . PHP_EOL . "All theme and plugin assets have been published...";

		return true;
	}

	/**
	 * Register all the gears and publish all their assets.
	 * 
	 * @param  array  $parameters
	 * @return void
	 */
	public function gear($parameters = array())
	{
		$publish = empty($parameters) ? null : array_shift($parameters);

		$this->publish($publish, path('gears') . DS, 'plugins');

		return true;
	}

	/**
	 * Register all the themes and publish all their assets.
	 * 
	 * @param  array  $parameters
	 * @return void
	 */
	public function theme($parameters = array())
	{
		$publish = empty($parameters) ? null : array_shift($parameters);

		$this->publish($publish, path('themes') .  DS, 'themes');

		return true;
	}

	/**
	 * Publish a theme or plugins assets.
	 * 
	 * @param  string  $publish
	 * @param  string  $path
	 * @param  string  $type
	 * @return void
	 */
	private function publish($publish, $path, $type)
	{
		if(file_exists($path))
		{
			ob_start();

			$publisher = new Laravel\CLI\Tasks\Bundle\Publisher;

			$published = 0;

			foreach(new FilesystemIterator($path) as $item)
			{
				if($item->isDir() and ($publish == $item->getFilename() or is_null($publish)))
				{
					$name = $item->getFilename();

					if(file_exists($item->getPathname() . DS . 'public'))
					{
						// To publish the assets we must first register the bundle. Afterwards
						// we can run the publish task.
						Bundle::register($this->name($name, $type), array(
							'location' => 'path: ' . $item->getPathname(),
							'handles'  => null,
							'auto'	   => false
						));

						ob_start();

						$publisher->publish($this->name($name, $type));

						if(str_contains(ob_get_clean(), 'published'))
						{
							echo "[{$type}] Assets for '{$name}' have been published." . PHP_EOL;

							$published++;
						}
						else
						{
							echo "[{$type}] Could not publish assets for '{$name}'." . PHP_EOL;
						}
					}
				}
			}

			if(($string = ob_get_clean()) == '')
			{
				echo "[{$type}] There were no assets to publish.";
			}
			else
			{
				echo $string . "[{$type}] Total published: {$published}" . PHP_EOL . PHP_EOL;
			}
		}
		else
		{
			echo "Could not locate the Feather {$type} directory. Please check your installation.";
		}
	}

	/**
	 * Name given to theme or gear.
	 * 
	 * @param  string  $name
	 * @param  string  $type
	 * @return string
	 */
	private function name($name, $type)
	{
		return "feather/{$type}/{$name}";
	}

}