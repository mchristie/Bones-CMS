<?php

namespace Christie\Bones\Interfaces;

interface FieldtypeInterface
{
    public function __construct($field_data, $field, $entry);

    // Display the value of the field, called by __toString
    public function render();

    // BOOL Indicating if this field show in the admin area
    public function displaysEditForm();

    // The field where admins can modify content
    public function editForm();

    // BOOL Indicating if this field has settings to modify
    public function displaysSettingsForm();

    // Show the form where admins can edit settings
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
    public function save();

    // Create the field_data instance, and anything else which is needed
    public function findOrCreateFieldData( \Christie\Bones\Models\Entry $entry );

    // BOOL displaysSettingForm - Does the field have settings to modify
    // View settingForm - Display the form where an admin can change settings
}