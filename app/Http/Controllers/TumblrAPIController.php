<?php
/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 13.08.2017
 * Time: 23:08
 */

namespace App\Http\Controllers;

Use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TumblrAPIController extends Controller
{
    /** @var  String */
    private $API_KEY, $SECRET_KEY;

    /** @var  String */
    public $user;

    /** @var  Client */
    public $guzzle;

    function init() {
        $this->API_KEY = config('tumblr-api.api-key', null);
        $this->SECRET_KEY = config('tumblr-api.secret-key', null);

        $this->guzzle = new Client([
            'base_uri' => 'https://api.tumblr.com/v2/',
        ]);
    }

    function isValidUser() {
        if (!empty($this->user)) {
            try {
                $response = $this->guzzle->request('GET', 'blog/' . $this->user . '/info?api_key=' . $this->API_KEY);
                return true;
            } catch (RequestException $e) {
                if ($e->getCode() == 404) {
                    echo 'User not found';
                } else {
                    echo 'Unknown exception: ' . $e->getCode();
                }
            }
        }
        return false;
    }
}