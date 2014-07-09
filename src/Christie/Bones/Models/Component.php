<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Component extends Eloquent {

    use BonesModel;

    protected $fillable = array('site_id', 'in_menu', 'type', 'settings');

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

    public function initialize() {
        // We only want to initialize this field once, so return if it exists
        if ($this->initialized) return $this->initialized;

        $bones = \App::make('bones');
        $class = $bones->components( $this->type );

        $this->initialized = new $class($bones, $this);

        $this->jsonFields( $this->initialized );

        $this->initialized->initialize();

        return $this;

    }

}