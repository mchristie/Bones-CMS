<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \URL;
use \Auth;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\User;

class UsersController extends BonesController {

    public function showUsers() {
        return $this->bones->view('admin.users.index', array(
            'users' => User::currentSite()->get()
        ));
    }

    public function editUser($id = 'new') {
        if ($id == 'new') {
            $user = new User;
        } else {
            $user = User::currentSite()->find($id);
        }

        // Don't allow users to edit users above their level
        if ($user->level > Auth::user()->level || Input::get('level') > Auth::user()->level)
            return Redirect::route('users');

        // Save the user
        if (Request::getMethod() == 'POST') {
            $user->username = Input::get('username');
            $user->email    = Input::get('email');
            $user->level    = Input::get('level');
            $user->site_id  = Input::get('site_id') ?: null;
            if (Input::get('password'))
                $user->password = \Hash::make(Input::get('password'));
            $user->save();

            return Redirect::route('users');
        }

        return $this->bones->view('admin.users.edit', array(
            'user' => $user
        ));
    }

}
