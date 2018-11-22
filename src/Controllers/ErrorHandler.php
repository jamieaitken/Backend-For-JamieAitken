<?php
/**
 * Created by PhpStorm.
 * User: jamieaitken
 * Date: 24/04/2018
 * Time: 21:09
 */

namespace App\Controllers;

use App\Controllers\Integrations\GiphyController;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandler
{
    public function __invoke($request, $response, $exception)
    {

        $getAGif = new GiphyController();
        $gif = $getAGif->getGif()['reason']['images']['original']['url'];

        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('<!DOCTYPE html>
<html>
<head>
	<title>Whoops</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
		<img class="mx-auto rounded d-block" src=' . $gif . '/>
    	<h3 class="text-center">
    		<a href="http://jamieaitken.com">Ohhh nooo</a>
    	</h3>
	</div>
</body>
</html>');

    }
}