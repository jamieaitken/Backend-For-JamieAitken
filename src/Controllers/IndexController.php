<?php
/**
 * Created by PhpStorm.
 * User: jamieaitken
 * Date: 27/03/2018
 * Time: 17:28
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class IndexController
{
    public function getRoute(Request $request, Response $response)
    {
        $send = $this->get();
        return $response->withJson($send, $send['statusCode']);
    }

    private function get()
    {
        return ['statusCode' => 200, 'reason' => 'Hello'];
    }
}