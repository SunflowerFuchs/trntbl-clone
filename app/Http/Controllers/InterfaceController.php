<?php
/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 13.08.2017
 * Time: 23:09
 */

namespace App\Http\Controllers;


class InterfaceController extends Controller
{
    /** @var  TumblrAPIController */
    private $API;

    function showData(string $username) {
        $this->API = new TumblrAPIController();
        $this->API->user = $username;

        $data = $this->loadTumblrData($username);

        if (is_a($data, 'VIEW')) {
            return $data;
        }
        //var_dump($data);

        return null; //Placeholder
    }

    function loadTumblrData($username) {
        $this->API->init();

        if ($this->API->isValidUser()) {
            //do something
            return 'YOU! ARE! VALID!';
        } else {
            return 'error';
            /* pseudo-code for loading an error-view
            return view('loadErrorViewHere!!!', ['error' => [
                'code' => 404,
                'msg' => 'User not found',
            ]]);
            */
        }
    }
}