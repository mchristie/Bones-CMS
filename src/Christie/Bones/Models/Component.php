<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Component extends Eloquent {

    use BonesModel;

    protected $fillable = array('site_id', 'in_menu', 'type', 'settings');

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

}