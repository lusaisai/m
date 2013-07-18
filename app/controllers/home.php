<?php

namespace app\controllers;

use \mako\View;

class Home extends \mako\Controller
{
	public function action_index()
	{
		return new View('home.index');
	}
}