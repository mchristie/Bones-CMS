<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Site extends Eloquent {

    use BonesModel;

    public function channels() {
        return $this->hasMany('Christie\Bones\Models\Channel');
    }
}