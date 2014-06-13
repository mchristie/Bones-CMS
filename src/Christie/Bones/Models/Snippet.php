<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Snippet extends Eloquent {

    use BonesModel;

    protected $fillable = array('site_id', 'key', 'content');

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

    public function getPreviewAttribute() {
        return \Illuminate\Support\Str::words( e($this->content), 20);
    }

    public function render() {
        return $this->content;
    }

    public function __toString() {
        return $this->render();
    }

}