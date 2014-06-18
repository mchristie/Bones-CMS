<?php

namespace Christie\Bones\Fieldtypes;

class WysiwygField extends BonesField implements \Christie\Bones\Interfaces\FieldtypeInterface {

    public static $title = 'WYSIWYG editor';

    private $error = false;

    /*
     *  Show the field data, as we would on the front-end
     */
    public function render() {
        if ($this->field_data == null || $this->field_data->text_data == null) {
            return '';
        } else {
            return $this->field_data->text_data;
        }
    }

    /*
     *  Return a short section of the content without HTML
     */
    public function preview($words = 10) {
        if ($this->field_data == null || $this->field_data->text_data == null)
            return '';

        $text = strip_tags($this->field_data->text_data);
        return \Str::words($text, $words);
    }

    /*
     *  Create a new FieldData instance, and anything else we need
     */
    public function findOrCreateFieldData( \Christie\Bones\Models\Entry $entry ) {
        // Find custom data and return it if we can
        $field_data = $entry->fielddata()->where('field_id', $this->id)->first();
        if ($field_data) return $field_data;

        return \Christie\Bones\Models\FieldData::create(array(
            'entry_id'  => $entry->id,
            'field_id'  => $this->id,
            'text_data' => ''
        ));
    }

    /*
     *  Return a field, or anything else we need, for the entry form
     *  TODO: This should probably use views and show it's own label etc
     */
    public function editForm() {
        // We need to ensure the JS file is included
        $bones = \App::make('bones');
        $bones->includeJS('/packages/christie/bones/tinymce/tinymce.min.js');

        // In-case we have multiple editors, give it a (probably) unique ID
        $uid = rand(0, 999);

        // Initialize the editor
        \View::inject('additional_js', '<script type="text/javascript">tinymce.init({selector: "#wysiwyg-'.$uid.'"});</script>');

        // And return the HTML
        return '<textarea class="form-control" rows="10" name="'.$this->name.'" id="wysiwyg-'.$uid.'">'.$this->field_data->text_data.'</textarea>';
    }

    /*
     *  Fill the field from the input array
     *  Store the memory to re-populate the form, but DON'T save it
     */
    public function populate( Array $input ) {
        if (array_key_exists($this->name, $input))
            $this->field_data->text_data = $input[$this->name];
    }

    /*
     *  Perform validation on the input array, and return true/false for valid/not-valid
     */
    public function validates() {
        if (strlen($this->field_data->text_data) < 2) {
            $this->error = 'The field must be at at least 2 characters long.';
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