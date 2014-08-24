<?php

namespace Christie\Bones\Fieldtypes;

class CheckboxesField extends BonesField implements \Christie\Bones\Interfaces\FieldtypeInterface {

    public static $title = 'Checkboxes';

    private $error = false;

    // Settings JSON fields
    public $settings_json_fields = array(
        'settings' => array('options')
    );

    public $settings_json_attributes = array(
        'options' => 'settings'
    );

    public $settings_json_defaults = array(
        'options' => array()
    );

    // Field data settings fields
    public $data_json_fields = array(
        'text_data' => array('selected')
    );

    public $data_json_attributes = array(
        'selected' => 'text_data'
    );

    public $data_json_defaults = array(
        'selected' => array()
    );

    /*
     *  Show the field data, as we would on the front-end
     */
    public function render() {
        if ($this->field_data == null || !$this->field_data->selected) {
            return '';
        } else {
            return implode(', ', $this->field_data->selected);
        }
    }

    /*
     *  Create a new FieldData instance, and anything else we need
     */
    public function findOrCreateFieldData( \Christie\Bones\Models\Entry $entry ) {
        // Find custom data and return it if we can
        $field_data = $entry->fielddata()->where('field_id', $this->id)->first();
        if ($field_data) return $field_data;

        return \Christie\Bones\Models\FieldData::create(array(
            'entry_id'    => $entry->id,
            'field_id'    => $this->id,
            'text_data'   => '[]'
        ));
    }

    /*
     *  Return a field, or anything else we need, for the entry form
     *  TODO: This should probably use views and show it's own label etc
     */
    public function editForm() {

        return $this->bones_forms->field(array(
            'type'    => 'checkboxes',
            'name'    => $this->name.'[]',
            'title'   => $this->title,
            'values'  => $this->options,
            'checked' => $this->selected ?: array(),
            'help'    => $this->help
        ));
    }

    /*
     *  Show the settings form
     */
    public function settingsForm() {

        return $this->bones_forms->field(array(
            'type'    => 'textarea',
            'title'   => 'Options',
            'name'    => 'options',
            'value'   => implode(PHP_EOL, $this->options),
            'help'    => 'Enter one option per line.'
        ));
    }

    /*
     *  Fill the field from the input array
     *  Store the memory to re-populate the form, but DON'T save it
     */
    public function populate( Array $input ) {
        if (array_key_exists($this->name, $input))
            $this->field_data->selected = $input[$this->name];

    }

    /*
     *  Fill the settings field from the input array
     */
    public function saveSettings( Array $input ) {
        if (array_key_exists('options', $input))
            $this->field->options = preg_split('#[\r\n]#', $input['options'], -1, PREG_SPLIT_NO_EMPTY);

        return true;
    }

    /*
     *  Perform validation on the input array, and return true/false for valid/not-valid
     */
    public function validates() {
        /*
        if (strlen($this->field_data->string_data) < $this->min_length) {
            $this->error = 'The field must contain at least '.$this->min_length.' characters';
            return false;
        }
        */

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

}