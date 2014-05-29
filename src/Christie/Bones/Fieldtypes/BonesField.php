<?php

namespace Christie\Bones\Fieldtypes;

class BonesField {

    protected $field_data;
    protected $channel;

    public function __construct($field_data, $field, $entry) {

        $this->field      = $field;

        if (!$field_data)
            $field_data = $this->findOrCreateFieldData($entry);

        $this->field_data = $field_data;
    }

    public function findOrCreateFieldData( \Christie\Bones\Models\Entry $entry ) {
        // Find custom data and return it if we can
        $field_data = $entry->fielddata()->where('field_id', $this->id)->first();
        if ($field_data) return $field_data;

        // Create a new field data record
        return \Christie\Bones\FieldData::create(array(
            'entry_id' => $entry->id,
            'field_id' => $this->id
        ));
    }

    public function __toString() {
        return $this->render();
    }

    public function displaysEditForm() {
        return true;
    }

    /*
     *  When we access a value on any BonesField, we're really looking for the field details
     */
    public function __get($field) {
        return $this->field->$field;
    }

}