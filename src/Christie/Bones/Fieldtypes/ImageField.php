<?php

namespace Christie\Bones\Fieldtypes;

use \Christie\Bones\Models\Image;

class ImageField extends BonesField implements \Christie\Bones\Interfaces\FieldtypeInterface {

    public static $title = 'Image';

    private $error = false;

    /*
     *  Show the field data, as we would on the front-end
     */
    public function render() {
        $image = $this->getImage();
        if (!$image) return '';

        return $image->url;
    }

    /*
     *  Return a URL to a copy of this image at a specific size
     */
    public function url($width, $height = null) {
        $image = $this->getImage();
        if (!$image) return '';

        return $image->url($width, $height);
    }

    /*
     *  Return a path the the image at a certain size
     */
    public function path($width, $height = null) {
        $image = $this->getImage();
        if (!$image) return '';

        return $image->path($width, $height);
    }

    /*
     *  Return the referenced image or null
     */
    public function getImage() {
        if (!$this->field_data || !$this->field_data->integer_data)
            return null;

        return Image::find( $this->field_data->integer_data );
    }

    /*
     *  Return a field, or anything else we need, for the entry form
     *  TODO: This should probably use views and show it's own label etc
     */
    public function editForm() {

        // And return the HTML
        return '<input class="form-control" name="'.$this->name.'" value="'.$this->field_data->integer_data.'" placeholder="Image ID" />';
    }

    /*
     *  Fill the field from the input array
     *  Store the memory to re-populate the form, but DON'T save it
     */
    public function populate( Array $input ) {
        if (array_key_exists($this->name, $input))
            $this->field_data->integer_data = $input[$this->name];
    }

    /*
     *  Perform validation on the input array, and return true/false for valid/not-valid
     */
    public function validates() {
        // Check image ID is valid

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