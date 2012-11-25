<?php

class HomeController extends Controller {

	public function showHomepage()
	{
		return View::make('hello');
	}

}