<?php
/**
 * Created by PhpStorm.
 * User: jamieaitken
 * Date: 24/04/2018
 * Time: 20:44
 */

namespace App\Controllers\Integrations;

use Curl\Curl;
use Slim\Http\Request;
use Slim\Http\Response;

class GiphyController
{
    private $baseEndpoint = 'https://api.giphy.com';

    public function getGifRoute(Request $request, Response $response)
    {
        $send = $this->getGif();

        return $response->withJson($send, $send['statusCode']);
    }

    public function getGif()
    {
        $responseStructure = [
            'reason' => '',
            'statusCode' => ''
        ];
        

        $request = new Curl($this->baseEndpoint);
        $request->get('/v1/gifs/random', [
            'api_key' => getenv('GIPHY_API_KEY'),
            'tag' => 'hmm'
        ]);

        $responseStructure['statusCode'] = $request->httpStatusCode;
        $responseStructure['reason'] = $request->response;

        return $responseStructure;
    }
}