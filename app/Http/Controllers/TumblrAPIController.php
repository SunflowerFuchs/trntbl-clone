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

    /** @var array */
    private $patterns = [
        '^https?:\/\/(www\.)?tumblr\.com\/audio_file.*$',
        '^https?:\/\/a\.tumblr\.com\/tumblr_.*$',
    ];

    /**
     * TumblrAPIController constructor.
     * @param string $username
     * @throws \Exception
     */
    function __construct(string $username) {
        $this->user = $username;

        $this->log = new Logger('TumblrAPIControllerLog');
        $this->log->pushHandler(new StreamHandler(storage_path() . '/logs/TumblrAPIController_debug.log', Logger::DEBUG));
        $this->log->pushHandler(new StreamHandler(storage_path() . '/logs/TumblrAPIController.log', Logger::INFO));
        $this->log->pushHandler(new StreamHandler(storage_path() . '/logs/TumblrAPIController_error.log', Logger::WARNING));
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
                    $this->log->log(Logger::ERROR, 'Unknown exception: "' . $e->getMessage() . '"; User: ' . $this->user);
                    return view('trntbl.main', [
                        'error' => "Unknown error. \n Try again later, and if it persists, contact me.",
                    ]);
                }
            }
        }
        return false;
    }

    function loadAudioPosts(int $count = 20, int $offset = 0, string $tag = null) {
        try {
            $options = [
                'api_key' => $this->API_KEY,
                'limit' => $count,
                'offset' => $offset,
                'filter' => 'text',
            ];

            if ($tag != null) {
                $options['tag'] = htmlentities($tag);
            }

            $response = $this->guzzle->request('GET', 'blog/' . $this->user . '/posts/audio', [
                'query' => $options
            ]);

            $this->log->log(Logger::DEBUG, 'API URL: https://api.tumblr.com/v2/blog/' . $this->user . '/posts/audio?' . http_build_query($options,'','&'));

            $data = json_decode($response->getBody(), true)['response'];
            $data = $this->filterPosts($data);
            if (!isset($data['total_posts']) || $data['total_posts'] == 0) {
                return view('trntbl.main', [
                    'error' => 'No audio posts found!',
                ]);
            }
            return $data;
        } catch (RequestException $e) {
            $this->log->log(Logger::WARNING, 'Tumblr-API not reachable; Error: "' . $e->getMessage() . '"');
            return view('trntbl.main', [
                'error' => 'Couldn\'t load audio posts, maybe tumblrs API is down at the moment...',
            ]);
        }
    }

    function loadPostByID(string $id) {
        $options = [
            'api_key' => $this->API_KEY,
            'filter' => 'text',
            'id' => $id,
        ];

        try {
            $response = $this->guzzle->request('GET', 'blog/' . $this->user . '/posts/audio', [
                'query' => $options
            ]);
        } catch (RequestException $e) {
            $this->log->log(Logger::WARNING, 'Tumblr-API not reachable; Error: "' . $e->getMessage() . '"');
            return view('trntbl.main', [
                'error' => 'Couldn\'t load audio posts, maybe tumblrs API is down at the moment...',
            ]);
        }

        $data = json_decode($response->getBody(), true)['response'];
        if (!isset($data['total_posts']) || $data['total_posts'] == 0) {
            return view('trntbl.main', [
                'error' => 'No audio posts found!',
            ]);
        }
        return $data;
    }

    function filterPosts(array $data)
    {
        $validPosts = ['posts' => []];
        if (empty($data) || !is_array($data) || empty($data['posts'])) {
            return [];
        }

        foreach ($data['posts'] as $key => $post) {
            foreach ($this->patterns as $pattern) {
                $pattern = '/' . $pattern. '/';
                if (isset($post['audio_url']) && preg_match($pattern, $post['audio_url'])) {
                    $validPosts['posts'][] = $post;
                    break;
                }
            }
        }

        return array_merge($data, $validPosts);
    }
}