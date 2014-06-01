<?php

namespace Christie\Bones\Models;

use \Eloquent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    protected $table = 'users';

    protected $hidden = array('password');

    /*
     *  Laravel's auth methods
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }
    public function getAuthPassword() {
        return $this->password;
    }
    public function getRememberToken() {
        return $this->remember_token;
    }
    public function setRememberToken($value) {
        $this->remember_token = $value;
    }
    public function getRememberTokenName() {
        return 'remember_token';
    }
    public function getReminderEmail() {
        return $this->email;
    }

}
