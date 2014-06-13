<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Site extends Eloquent {

    use BonesModel;

    public function channels() {
        return $this->hasMany('Christie\Bones\Models\Channel');
    }

    public function users() {
        return $this->hasMany('Christie\Bones\Models\User');
    }

    public function snippets() {
        return $this->hasMany('Christie\Bones\Models\Snippet');
    }

    public function components() {
        return $this->hasMany('Christie\Bones\Models\Component');
    }
}