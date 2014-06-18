<?php

namespace Christie\Bones\Interfaces;

interface WidgetInterface
{

    // Display the value of the widget, called by __toString
    public function render();

    // BOOL Indicating if this field show in the admin area
    public function displaysSettingsForm();

    // The field where admins can modify content
    public function settingsForm();

    /*
     *  Select the appropriate data from the POST input but DON'T save it
     *  The data should be stored in memory for displaying the form again if necessary
     */
    public function populate( Array $input );

    // Return BOOL indicating if the field data from populate is valid
    public function validates();

    // Return BOOL indication if the field has errors to show from validation
    public function hasErrors();

    // Return errors for the field
    public function showErrors();

    // Save the data from populate
    // public function save();

}