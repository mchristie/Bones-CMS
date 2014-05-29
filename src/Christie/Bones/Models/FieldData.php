<?php

namespace Christie\Bones\Models;

use \Eloquent;

class FieldData extends Eloquent {

    use BonesModel;

    protected $table = 'field_data';

    protected $fillable = array('entry_id', 'field_id', 'integer_data', 'string_data', 'text_data');

    public function entry() {
        return $this->belongsTo('Christie\Bones\Models\Entry');
    }

    public function field() {
        return $this->belongsTo('Christie\Bones\Models\Field');
    }
}