<?php

namespace Christie\Bones\Widgets;

use Christie\Bones\Models\Channel;

class CustomViewWidget extends BonesWidget implements \Christie\Bones\Interfaces\WidgetInterface {

    public $title = 'Custom view';

    // Settings JSON fields
    public $settings_json_fields = array(
        'settings' => array('view')
    );

    public $settings_json_attributes = array(
        'view' => 'settings'
    );

    public $settings_json_defaults = array(
        'view' => null
    );

    // Display the value of the widget, called by __toString
    public function render() {
        if ($this->view) {
            $this->bones = \App::make('bones');
            return $this->bones->view( $this->view, json_decode($this->settings, true) );
        }

        return $this->bones->view( $this->view );
    }

    public function hasField($field) {
        return in_array($field, array('title', 'view')) ? true : false;
    }

    // BOOL Indicating if this field show in the admin area
    public function displaysSettingsForm() {
        return true;
    }

    // The field where admins can modify content
    public function settingsForm() {
        return \BonesForms::fields(array(
            array(
                'title'     => 'View',
                'name'      => 'view',
                'type'      => 'text',
                'value'     => $this->view
            )
        ));
    }

    /*
     *  Select the appropriate data from the POST input but DON'T save it
     *  The data should be stored in memory for displaying the form again if necessary
     */
    public function populate( Array $input ) {

    }

    // Return BOOL indicating if the field data from populate is valid
    public function validates() {
        return true;
    }

    // Return BOOL indication if the field has errors to show from validation
    public function hasErrors() {
        return false;
    }

    // Return errors for the field
    public function showErrors() {
        return 'No errors';
    }

}