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
        if ($field_data) return $field_data->initialize($this);

        // Create a new field data record
        return \Christie\Bones\Models\FieldData::create(array(
            'entry_id' => $entry->id,
            'field_id' => $this->id
        ))->initialize($this);
    }

    public function __toString() {
        return $this->render();
    }

    public function displaysEditForm() {
        return true;
    }

    /*
     *  Save the data stored from populate
     */
    public function save() {
        return $this->field_data->save();
    }

    /*
     *  Return BOOL, true if there are errors
     */
    public function hasErrors() {
        return $this->error ? true : false;
    }

    /*
     *  Return the errors for this field
     */
    public function showErrors() {
        return $this->error;
    }

    /*
     *  When we access a value on any BonesField, we're really looking for the field details
     */
    public function __get($field) {

        if ($this->field_data && $this->field_data->hasField($field))
            return $this->field_data->$field;

        return $this->field->$field;
    }

}