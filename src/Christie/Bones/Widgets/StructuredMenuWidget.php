<?php

namespace Christie\Bones\Widgets;

use Christie\Bones\Models\Channel;

class StructuredMenuWidget extends BonesWidget implements \Christie\Bones\Interfaces\WidgetInterface {

    public $title = 'Structured menu';

    // Settings JSON fields
    public $settings_json_fields = array(
        'settings' => array('channel_id')
    );

    public $settings_json_attributes = array(
        'channel_id' => 'settings'
    );

    public $settings_json_defaults = array(
        'channel_id' => array()
    );

    // Display the value of the widget, called by __toString
    public function render() {
        $channel = Channel::find($this->channel_id);
        return $this->parseEntryTree( $channel->entryTree() );
    }

    public function parseEntryTree($entries, $dropdown = false) {
        $str = $dropdown ?
            '<ul class="dropdown-menu" role="menu">' :
                '<ul class="nav navbar-nav">';

        foreach ($entries as $entry) {
            if ($entry->show_in_menu) continue;

            $str .= '<li>';
            if ($entry->children->count()) {
                $str .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$entry->title.' <span class="caret"></span></a>';
                $str .= $this->parseEntryTree($entry->children, true);
            } else {
                $str .= $entry->link;
            }

            $str .= '</li>';
        }

        $str .= '</ul>';

        return $str;
    }

    public function hasField($field) {
        return in_array($field, array('title', 'channel_id')) ? true : false;
    }

    // BOOL Indicating if this field show in the admin area
    public function displaysSettingsForm() {
        return true;
    }

    // The field where admins can modify content
    public function settingsForm() {
        return \BonesForms::fields(array(
            array(
                'title'     => 'Channel',
                'name'      => 'channel_id',
                'type'      => 'select',
                'options'   => 'channels',
                'value'     => $this->channel_id
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