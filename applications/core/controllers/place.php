<?php

class Feather_Core_Place_Controller extends Feather_Base_Controller {

	public function get_index($id, $slug)
	{
		if(!$place = Feather\Core\Place::one($id, $this->config->get('feather: db.discussions.per_page')))
		{
			
		}
		
		$this->breadcrumbs->drop($place);

		$this->layout->nest('content', 'feather core::place.view', compact('place'))
					 ->with('title', $place->name);
	}

}