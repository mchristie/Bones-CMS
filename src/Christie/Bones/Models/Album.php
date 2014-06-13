<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Album extends Eloquent {

    use BonesModel;

    protected $fillable = array('title', 'site_id');

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

    public function images() {
        return $this->hasMany('\Christie\Bones\Models\Image');
    }

}