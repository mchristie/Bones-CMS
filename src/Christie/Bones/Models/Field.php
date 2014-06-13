<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Field extends Eloquent {

    use BonesModel;

    private $initialized = false;

    public function channel() {
        return $this->belongsTo('Christie\Bones\Models\Channel');
    }

    public function getFieldTypeTitleAttribute() {
        $bones = \App::make('bones');
        $class = $bones->fieldTypes($this->field_type);
        return $class::$title;
    }

    public function initialize( $field_data, Entry $entry) {
        // We only want to initialize this field once, so return if it exists
        if ($this->initialized) return $this->initialized;

        $bones = \App::make('bones');

        $this->initialized = $bones->fieldType($field_data, $this, $entry);

        return $this->initialized;
    }

    /*
     *  Setup events, such as recoding JSON fields before save
     */
    public static function boot() {
        parent::boot();

        Field::saving(function($field) {
            $field->saveJsonFields();
        });
    }

}