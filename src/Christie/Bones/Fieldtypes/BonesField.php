<?php

namespace Christie\Bones\Fieldtypes;

class BonesField {

    protected $entry;
    protected $field;
    protected $field_data;

    public $data_json_fields         = false;
    public $data_json_attributes     = false;
    public $data_json_defaults       = false;

    public $settings_json_fields     = false;
    public $settings_json_attributes = false;
    public $settings_json_defaults   = false;

    public function __construct($field_data, $field, $entry) {

        if ($field) {
            $this->field = $field;
            $this->field->jsonFields($this);
        }

        if (!$field_data && $entry) {
            $field_data = $this->findOrCreateFieldData($entry)->jsonFields($this);

        } else if ($field_data) {
            $field_data->jsonFields($this);
        }

        $this->field_data = $field_data;
    }

    public function findOrCreateFieldData( \Christie\Bones\Models\Entry $entry ) {
        // Find custom data and return it if we can
        $field_data = $entry->fielddata()->where('field_id', $this->id)->first();
        if ($field_data) return $field_data;

        // Create a new field data record
        return \Christie\Bones\Models\FieldData::create(array(
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

    public function displaysSettingsForm() {
        return true;
    }

    public function saveSettings( Array $input ) {
        return true;
    }

    /*
     *  Save the data stored from populate
     */
    public function save() {
        if ($this->field)
            $this->field->save();

        if ($this->field_data)
            $this->field_data->save();
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

        if ($field == 'bones_forms')
            return $this->bones_forms = \App::make('bonesforms');

        if ($this->field_data && $this->field_data->hasField($field))
            return $this->field_data->$field;

        if ($this->field && $this->field->hasField($field))
            return $this->field->$field;
    }

    /*
     *  When we access a value on any BonesField, we're really looking for the field details
     */
    public function __set($field, $value) {

        if ($this->field_data && $this->field_data->hasField($field))
            return $this->field_data->$field = $value;

        if ($this->field && $this->field->hasField($field))
            return $this->field->$field = $value;

        return $this->$field = $value;
    }

    /*
     *  Show the settings form
     */
    public function settingsForm() {
        return '';
    }

}