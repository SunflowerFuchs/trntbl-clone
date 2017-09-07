<?php
/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 13.08.2017
 * Time: 23:09
 */

namespace App\Http\Controllers;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class InterfaceController extends Controller
{
    /** @var  TumblrAPIController */
    private $API;

    function showData(string $username) {
        $this->API = new TumblrAPIController(strtolower($username));

        $data = $this->loadTumblrData();

        if ($data instanceof View) {
            return $data;
        }

        $paginatedData = new LengthAwarePaginator($data['posts'], $data['total_posts'], 20, LengthAwarePaginator::resolveCurrentPage(), [
            'path' => '/' . $username,
        ]);

        return view('trntbl.list', [
            'posts' => $paginatedData,
            'total_posts' => $data['total_posts'],
            'offset' => (LengthAwarePaginator::resolveCurrentPage() - 1) * 20,
            'volume' => isset($_GET['volume'])?$_GET['volume']:0.50,
            'toplay' => isset($_GET['toplay'])?$_GET['toplay']:0,
        ]);
    }

    function loadTumblrData() {
        $result = $this->API->isValidUser();

        if ($result === true) {
            $data = $this->API->loadAudioPosts(20, (LengthAwarePaginator::resolveCurrentPage() - 1) * 20);
            return $data;
        } else {
            if ($result instanceof View) {
                return $result;
            }

            return view('trntbl.main', [
                'error' => 'User not found'
            ]);
        }
    }
}