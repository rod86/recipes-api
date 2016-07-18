<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Laravel\Lumen\Routing\Controller as BaseController;


class Controller extends BaseController
{
	protected function _generateResponseData($data) {
	    return [
		    'status' => 200,
		    'data' => $data
	    ];
    }
}
