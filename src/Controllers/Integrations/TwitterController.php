<?php
/**
 * Created by PhpStorm.
 * User: jamieaitken
 * Date: 24/03/2018
 * Time: 21:28
 */

namespace App\Controllers\Integrations;

use App\Controllers\CacheHandler;
use Curl\Curl;
use Slim\Http\Request;
use Slim\Http\Response;

class TwitterController
{
    private $baseUrl = 'https://api.twitter.com/1.1/users/show.json';

    public function getDetailsRoute(Request $request, Response $response)
    {
        $send = $this->getDetails();

        return $response->withJson($send, $send['statusCode']);
    }

    private function encodeKeys()
    {
        $consumerKey = urlencode(getenv('TWITTER_CONSUMER_KEY'));
        $consumerSecret = urlencode(getenv('TWITTER_CONSUMER_SECRET'));

        return base64_encode($consumerKey . ':' . $consumerSecret);
    }

    private function obtainBearerToken()
    {
        $postRequest = new Curl('https://api.twitter.com');
        $postRequest->setHeader('Authorization', 'Basic ' . $this->encodeKeys());
        $postRequest->setHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');
        $postRequest->post('/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

        return ['reason' => $postRequest->response, 'statusCode' => $postRequest->httpStatusCode];
    }


    private function getDetails()
    {

        $cache = new CacheHandler();

        $fetch = $cache->fetch('twitter');

        if (!is_bool($fetch)) {
            return [
                'statusCode' => 200,
                'reason' => $fetch
            ];
        }

        $responseStructure = [
            'reason' => '',
            'statusCode' => ''
        ];

        $keysTheFrontWantsForUser = ['name', 'location', 'description', 'profile_banner_url', 'profile_image_url'];

        $request = new Curl();
        $token = str_replace(':', '%', getenv('TWITTER_BEARER_TOKEN'));
        $request->setHeader('Authorization', 'Bearer ' . $token);
        $request->get($this->baseUrl, [
            'screen_name' => 'Jamie__Aitken',
            'id' => 24401846
        ]);

        $responseStructure['statusCode'] = $request->httpStatusCode;

        if ($request->error) {
            $responseStructure['reason'] = $request->response;
            return $responseStructure;
        }

        $nicifyStrucuture = [
            'user' => []
        ];

        foreach ($request->response as $key => $value) {
            if (!in_array($key, $keysTheFrontWantsForUser) || is_null($value)) {
                continue;
            }

            if ($key === 'profile_image_url') {
                $nicifyStrucuture['user'][$key] = str_replace('http://', 'https://', substr($value, 0, strpos($value, '_normal')) . '_400x400.jpg');
            } else {
                $nicifyStrucuture['user'][$key] = $value;
            }
        }
        $cache->save('twitter', $nicifyStrucuture);

        $responseStructure['statusCode'] = 200;
        $responseStructure['reason'] = $nicifyStrucuture;

        return $responseStructure;
    }
}