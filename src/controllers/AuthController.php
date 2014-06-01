<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \Auth;

class AuthController extends BonesController {

    public function showLogin() {

        // Try the login
        $error = false;
        if (Request::getMethod() =='POST') {
            if (Auth::attempt(Input::all())) {
                return Redirect::intended('entries');
            } else {
                $error = 'You login details were not accepted.';
            }
        }

        return $this->bones->view('admin.auth.login', array('error' => $error));
    }
}
