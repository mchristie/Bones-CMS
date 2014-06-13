<?php

namespace Christie\Bones\Models;

use \Eloquent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use BonesModel;

    public function site() {
        return $this->belongsTo('Christie\Bones\Models\Site');
    }

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

    /*
     *  Return BOOL indicating if the user is at or above the level
     */
    public function isLevel($level) {
        return $this->level >= $level;
    }
}
