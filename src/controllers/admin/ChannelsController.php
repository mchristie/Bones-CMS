<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \URL;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;
use Christie\Bones\Models\Field;
use Christie\Bones\Models\FieldData;

class ChannelsController extends BonesController {

    public function showChannels() {
        return $this->bones->view('admin.channels.index', array(
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
            $channel->fill(\Input::all());
            $channel->site_id = Input::get('site_id') ?: null;
            $channel->save();

            if ($channel->type == 'structured')
                $channel->structure();

            if (\Input::get('submit') == 'save-edit-fields') {
                return \Redirect::to( \URL::route('channel_fields', $channel->id));
            } else {
                return \Redirect::to( \URL::route('channels'));
            }
        }

        return $this->bones->view('admin.channels.edit', array(
            'channel' => $channel
        ));
    }

    public function editChannelFields($id) {
        $channel = Channel::find($id);
        $fields = $channel->fields;

        return $this->bones->view('admin.channels.fields', array(
            'channel'       => $channel,
            'fields'        => $fields,
            'field_types'   => $this->bones->fieldTypes()
        ));
    }

    public function editChannelField($channel_id, $field_id) {
        $channel = Channel::find($channel_id);

        if ($field_id == 'new') {
            $field = new Models\Field;
        } else {
            $field = $channel->fields()->find($field_id);
        }

        $field_type = $this->bones->fieldType(null, $field, null);

        if (Request::method() == 'POST') {
            $field->channel_id  = $channel_id;
            $field->label       = Input::get('label', 'New field');
            $field->name        = Input::get('name', 'new-field-'.rand(1,99));
            $field->field_type  = Input::get('field_type');

            if ($field_type)
                $field_type->saveSettings( Input::all() );

            $field->save();

            // Redirect back to fields if we weren't adding a new one
            if (Input::get('label')) {
                return Redirect::route('channel_fields', $channel_id );

            // Else, redirect to the edit page
            } else {
                return Redirect::route('channel_field', array($channel->id, $field->id) );
            }
        }

        return $this->bones->view('admin.channels.edit_field', array(
            'channel' => $channel,
            'field'   => $field_type,
            'field_types' => $this->bones->fieldTypes()
        ));
    }

    function deleteChannelField($channel_id, $field_id) {
        FieldData::where('field_id', $field_id)->delete();
        Field::where('id', $field_id)->delete();

        return Redirect::route('channel_fields', $channel_id );
    }

}
