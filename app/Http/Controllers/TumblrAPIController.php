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
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class TumblrAPIController extends Controller
{
    /** @var  String */
    private $API_KEY, $SECRET_KEY;

    /** @var  String */
    public $user;

    /** @var  Client */
    public $guzzle;

    /** @var  Logger */
    public $log;

    function init() {
        $this->log = new Logger('TumblrAPIControllerLog');
        $this->log->pushHandler(new StreamHandler(storage_path() . '/logs/TumblrAPIController_debug.log', Logger::DEBUG));
        $this->log->pushHandler(new StreamHandler(storage_path() . '/logs/TumblrAPIController.log', Logger::INFO));
        $this->log->pushHandler(new StreamHandler(storage_path() . '/logs/TumblrAPIController_error.log', Logger::ERROR));
        $this->log->log(Logger::DEBUG, 'TumblrAPIController initializing');

        $this->API_KEY = config('tumblr-api.api-key', null);
        $this->SECRET_KEY = config('tumblr-api.secret-key', null);

        $this->guzzle = new Client([
            'base_uri' => 'https://api.tumblr.com/v2/',
        ]);
    }

    function isValidUser() {
        if (!empty($this->user)) {
            try {
                $response = $this->guzzle->request('GET', 'blog/' . $this->user . '/info', [
                    'query' => [
                        'api_key' => $this->API_KEY,
                    ],
                ]);
                if ($response->getStatusCode() == 200) {
                    $this->log->log(Logger::DEBUG, 'Valid user: "' . $this->user . '"');
                    return true;
                } else {
                    $this->log->log(Logger::WARNING, 'Unknown status code: "' . $response->getStatusCode() . '"; User: ' . $this->user);
                }
            } catch (RequestException $e) {
                if ($e->getCode() == 404) {
                    $this->log->log(Logger::INFO, 'User not found: "' . $this->user . '"');
                } else {
                    $this->log->log(Logger::WARNING, 'Unknown exception: "' . $e->getCode() . '"; User: ' . $this->user);
                }
            }
        }
        return false;
    }
}