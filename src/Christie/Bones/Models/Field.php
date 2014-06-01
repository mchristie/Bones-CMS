<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Field extends Eloquent {

    use BonesModel;

    private $initialized = false;

    public function channel() {
        return $this->belongsTo('Christie\Bones\Models\Channel');
    }

    public function initialize( $field_data, Entry $entry) {
        // We only want to initialize this field once, so return if it exists
        if ($this->initialized) return $this->initialized;

        $bones = \App::make('bones');

        $this->initialized = $bones->fieldType($field_data, $this, $entry);

        return $this->initialized;
    }

}