<?php

namespace Innoboxrr\ZoomSdk\Facades;

use Illuminate\Support\Facades\Facade;

class Zoom extends Facade
{

	protected static function getFacadeAccessor()
	{
		return 'zoom';
	}

}