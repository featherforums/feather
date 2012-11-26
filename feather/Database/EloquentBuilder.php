<?php namespace Feather\Database;

use Cache;
use Illuminate\Database\Eloquent\Builder;

class EloquentBuilder extends Builder {

	/**
	 * Find a model by its primary key.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function find($id, $columns = array('*'))
	{
		// If the item that is trying to be fetched is cachable and a cached copy exists then we'll
		// return the cached copy to save on database calls.
		if (isset($this->model->cachable[$this->model->table]))
		{
			list($group, $foreign) = $this->model->cachable[$this->model->table];

			if (Cache::has("{$group}_{$id}"))
			{
				return Cache::get("{$group}_{$id}");
			}
		}

		$this->query->where($this->model->getKeyName(), '=', $id);

		return $this->first($columns);
	}

}