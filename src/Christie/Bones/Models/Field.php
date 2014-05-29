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
        $bones = \App::make('bones');

        return $bones->fieldType($field_data, $this, $entry);
    }

}