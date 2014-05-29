<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \URL;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;
use Christie\Bones\Models\Widget;

class AdminController extends BonesController {

    public function showDashboard() {
        return $this->bones->view('admin.dashboard');
    }

    // Channels

    public function showChannels() {
        return $this->bones->view('admin.channels', array(
            'channels' => $this->bones->channels()
        ));
    }

    public function editChannel($id) {
        if ($id == 'new') {
            $channel = new Channel;
        } else {
            $channel = Channel::find($id);
        }

        if (\Request::method() == 'POST') {
            $channel->update(\Input::all());

            if ($channel->type == 'structured')
                $channel->structure();

            if (\Input::get('submit') == 'save-edit-fields') {
                return \Redirect::to( \URL::route('channel_fields', $channel->id));
            } else {
                return \Redirect::to( \URL::route('channels'));
            }
        }

        return $this->bones->view('admin.channel_edit', array(
            'channel' => $channel
        ));
    }

    public function editChannelEntries($id) {
        $channel = $this->bones->channel($id);
        if (!$channel) \App::abort(404);

        $this->bones->includeJS('/packages/christie/bones/jquery-ui-1.10.4.custom.min.js');
        $this->bones->includeJS('/packages/christie/bones/jquery.mjs.nestedSortable.js');
        $this->bones->includeCSS('/packages/christie/bones/jquery-ui-1.10.4.custom.min.css');

        $entries = $channel->entries()->restrict()->get();
        $structured = $channel->entryTree();

        return $this->bones->view('admin.channel_entries', array(
            'channel'       => $channel,
            'entries'       => $entries,
            'structured'    => $structured,
            'root'          => $channel->root()
        ));
    }

    public function editChannelFields($id) {
        $channel = Channel::find($id);
        $fields = $channel->fields;

        return $this->bones->view('admin.channel_fields', array(
            'channel'       => $channel,
            'fields'        => $fields,
            'field_types'   => $this->bones->fieldTypes()
        ));
    }

    public function editChannelField($channel_id, $field_id) {
        $channel = Channel::find($channel_id);

        if ($field_id == 'new') {
            $field = new Field;
        } else {
            $field = $channel->fields()->find($field_id);
        }

        if (Request::method() == 'POST') {
            $field->channel_id  = $channel_id;
            $field->label       = Input::get('label');
            $field->name        = Input::get('name');
            $field->field_type  = Input::get('field_type');
            $field->save();

            return Redirect::to( URL::route('channel_fields', $channel_id) );
        }

        return $this->bones->view('admin.channel_field', array(
            'channel' => $channel,
            'field'   => $field,
            'field_types' => $this->bones->fieldTypes()
        ));
    }

    // Entries

    public function showEntries() {
        $config = array_merge(\Input::all(), array(
            'with' => array('channel')
        ));

        $entries = $this->bones->entries($config);
        // $channels = $this->bones->channels();

        return $this->bones->view('admin.entries', array(
            'entries'  => $entries,
            'total'    => $this->bones->total
        ));
    }

    public function editEntry($id, $channel_id = null) {

        if ($id == 'new') {
            $channel = Channel::find($channel_id);
            $entry = Entry::create(array(
                'title'      => 'New '.\Str::singular($channel->title),
                'channel_id' => $channel_id
            ));
        } else {
            $entry   = $this->bones->entry($id);
            $channel = $entry->channel;
        }

        // Initialize all the custom field types
        $fields  = $channel->fieldsWithEntry($entry);

        // Should we save things
        if (Request::method() == 'POST') {

            // Populate and validate the entry first
            $entry->populate(Input::all());
            $validates = $entry->validates();

            // Now populate and validate all the custom fields
            foreach ($fields as &$field) {
                $field->populate(Input::all());

                if (!$field->validates()) $validates = false;
            }

            // If there were no errors, we can save everything
            if ($validates) {
                $entry->save();
                foreach ($fields as &$field) {
                    $field->save();
                }

                // And send the user back to the entry list
                // TODO: We should send a conformation message too
                return Redirect::route('entries');
            }
        }

        // Show the entry form
        return $this->bones->view('admin.entry', array(
            'channel' => $channel,
            'entry'   => $entry,
            'fields'  => $fields
        ));
    }

    // widgets

    public function showWidgets() {
        return $this->bones->view('admin.widgets', array(
            'widgets' => Widget::currentSite()->get()
        ));
    }

    public function editWidget($id) {
        $widget = Widget::currentSite()->find($id);

        return $this->bones->view('admin.widget_edit', array(
            'widget' => $widget
        ));
    }

}
