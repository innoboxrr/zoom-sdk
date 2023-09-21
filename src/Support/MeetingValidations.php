<?php

namespace Innoboxrr\ZoomSdk\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

trait MeetingValidations
{

	private function createValidation(array $data)
	{

	    $validator = Validator::make($data, [
	        'topic' => 'required|string|max:255',
	        'start_time' => 'required|string',
	        'duration' => 'required',
	        'timezone' => [
	            'required',
	            Rule::in(Constants::TIMEZONES)
	        ],
	        'password' => 'required|min:6|max:8',
	    ]);

	    if ($validator->fails()) {

	        throw new ValidationException($validator); 

	    }

	}

	private function updateValidation(array $data)
	{

	    $validator = Validator::make($data, [
	        'topic' => 'nullable|string|max:255',
	        'start_time' => 'nullable|string',
	        'duration' => 'nullable',
	        'timezone' => [
	            'nullable',
	            Rule::in(Constants::TIMEZONES)
	        ],
	        'password' => 'nullable|min:6|max:8',
	    ]);

	    if ($validator->fails()) {

	        throw new ValidationException($validator); 

	    }

	}

}