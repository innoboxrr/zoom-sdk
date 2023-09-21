<?php

use Illuminate\Support\Facades\Route;

use Innoboxrr\ZoomSdk\Facades\Zoom;

Route::get('test', function() {

	/*
	$config = [
		'account' => 'xtZVyd1ZRuqVxOfK1cq43A',
		'client' => 'TX58z1MRquk7trQi22ZQw',
		'secret' => '3cEFR9xZOQ4OONrSM54KI6wbUt40TDhe',
		'meeting' => [
            'topic' => 'Mi super reunión',
            'start_time' => now()->format('Y-m-d\TH:i:s'),
            'duration' => 60,
            'timezone' => 'America/Mexico_City',
            'password' => 'asd8fasd',
        ]
	];
	*/

	$meeting = Zoom::createMeeting();

	// $meeting = Zoom::getMeeting('83631610043');

	//$meeting = Zoom::updateMeeting('83631610043', ['meeting' => ['topic' => 'Meeting editado 1']]);

	// $meeting = Zoom::deleteMeeting('83631610043');

	dd($meeting);

});