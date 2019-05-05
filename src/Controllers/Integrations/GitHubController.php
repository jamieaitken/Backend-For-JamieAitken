<?php
/**
 * Created by PhpStorm.
 * User: jamieaitken
 * Date: 24/03/2018
 * Time: 20:22
 */

namespace App\Controllers\Integrations;

use App\Controllers\CacheHandler;
use Slim\Http\Request;
use Slim\Http\Response;
use Curl\Curl;

class GitHubController
{
    private $baseEndPoint = 'https://api.github.com/';


    public function getReposRoute(Request $request, Response $response)
    {
        $send = $this->getRepos();

        return $response->withJson($send, $send['statusCode']);
    }

    private function getRepos()
    {

        $responseStructure = [
            'reason' => '',
            'statusCode' => ''
        ];

        $keysTheFrontWants = ['id', 'name', 'html_url', 'description', 'language', 'created_at', 'updated_at'];

        $getRequest = new Curl($this->baseEndPoint);
        $getRequest->get('user/repos', [
            'access_token' => getenv('GITHUB_ACCESS_TOKEN'),
            'affiliation' => 'owner',
            'sort' => 'updated'
        ]);

        $responseStructure['statusCode'] = $getRequest->httpStatusCode;

        if ($getRequest->error) {
            $responseStructure['reason'] = $getRequest->response;
            return $responseStructure;
        }


        $nicifyResults = [];

        foreach ($getRequest->response as $key => $repo) {
            foreach ($repo as $k => $v) {
                if (!in_array($k, $keysTheFrontWants)) {
                    continue;
                }
                $nicifyResults[$key][$k] = $v;
            }
        }

        $responseStructure['statusCode'] = 200;
        $responseStructure['reason'] = $nicifyResults;

        return $responseStructure;
    }
}