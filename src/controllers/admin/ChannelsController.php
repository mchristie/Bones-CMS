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
            $channel->site_id = $this->bones->site()->id;
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

        if (Request::method() == 'POST') {
            $field->channel_id  = $channel_id;
            $field->label       = Input::get('label');
            $field->name        = Input::get('name');
            $field->field_type  = Input::get('field_type');
            $field->save();

            return Redirect::to( URL::route('channel_fields', $channel_id) );
        }

        return $this->bones->view('admin.channels.edit_field', array(
            'channel' => $channel,
            'field'   => $field,
            'field_types' => $this->bones->fieldTypes()
        ));
    }

}
