<?php

namespace Christie\Bones\Fieldtypes;

class EmailField extends BonesField implements \Christie\Bones\Interfaces\FieldtypeInterface {

    public static $title = 'Email';

    private $error = false;

    public $settings_json_fields = array(
        'settings' => array('required')
    );

    public $settings_json_attributes = array(
        'required' => 'settings'
    );

    public $settings_json_defaults = array(
        'required' => false
    );

    /*
     *  Show the field data, as we would on the front-end
     */
    public function render() {
        if ($this->field_data == null || $this->field_data->string_data == null) {
            return '';
        } else {
            return $this->field_data->string_data;
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
            'string_data' => ''
        ));
    }

    /*
     *  Return a field, or anything else we need, for the entry form
     *  TODO: This should probably use views and show it's own label etc
     */
    public function editForm() {
        $html = '<input class="form-control" name="'.$this->name.'" value="'.$this->field_data->string_data.'" />';

        if ($this->help)
            $html .= '<p class="help-block">'.$this->help.'</p>';

        return $html;
    }

    /*
     *  Show the settings form
     */
    public function settingsForm() {
        $bones_forms = \App::make('bonesforms');

        return $bones_forms->field(array(
            'type'    => 'checkbox',
            'title'   => 'Required',
            'name'    => 'required',
            'value'   => '1',
            'checked' => $this->required ? true : null
        ));
    }

    /*
     *  Fill the field from the input array
     *  Store the memory to re-populate the form, but DON'T save it
     */
    public function populate( Array $input ) {
        if (array_key_exists($this->name, $input))
            $this->field_data->string_data = $input[$this->name];
    }

    /*
     *  Fill the settings field from the input array
     */
    public function saveSettings( Array $input ) {
        if (array_key_exists('required', $input) && $input['required']) {
            $this->field->required = true;
        } else {
            $this->field->required = false;
        }

        return true;
    }

    /*
     *  Perform validation on the input array, and return true/false for valid/not-valid
     */
    public function validates() {
        if (!filter_var($this->field_data->string_data, FILTER_VALIDATE_EMAIL)) {
            $this->error = 'The field must contain a valid email address.';
            return false;
        }

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