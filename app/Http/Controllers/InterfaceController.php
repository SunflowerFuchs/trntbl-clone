<?php
/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 13.08.2017
 * Time: 23:09
 */

namespace App\Http\Controllers;


use Illuminate\View\View;

class InterfaceController extends Controller
{
    /** @var  TumblrAPIController */
    private $API;

    function showData(string $username) {
        $this->API = new TumblrAPIController();
        $this->API->user = $username;

        $data = $this->loadTumblrData($username);

        if ($data instanceof View) {
            return $data;
        }

        // TODO: create a view for the loaded data

        return null; //Placeholder
    }

    function loadTumblrData($username) {
        $this->API->init();

        if ($this->API->isValidUser()) {
            $data = $this->API->loadAudioPosts();
            return $data;
        } else {
            return view('trntbl.main', [
                'error' => 'User not found'
            ]);
        }
    }
}