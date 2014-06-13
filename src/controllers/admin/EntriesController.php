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

class EntriesController extends BonesController {

    public function editChannelEntries($id) {
        $channel = $this->bones->channel($id);
        if (!$channel) \App::abort(404);

        $this->bones->includeJS('/packages/christie/bones/jquery-ui-1.10.4.custom.min.js');
        $this->bones->includeJS('/packages/christie/bones/jquery.mjs.nestedSortable.js');
        $this->bones->includeCSS('/packages/christie/bones/jquery-ui-1.10.4.custom.min.css');

        $entries = $channel->entries()->restrict()->get();

        $data = array(
            'channel'       => $channel,
            'entries'       => $entries,
        );


        if ($channel->type == 'structured') {
            $structured         = $channel->entryTree();
            $data['structured'] = $structured;
            $data['root']       = $channel->root();
        }

        return $this->bones->view('admin.entries.channel_entries', $data);
    }

    public function showEntries() {
        $config = array_merge(\Input::all(), array(
            'with' => array('channel')
        ));

        $entries = $this->bones->entries($config);
        // $channels = $this->bones->channels();

        return $this->bones->view('admin.entries.index', array(
            'entries'  => $entries,
            'total'    => $this->bones->total
        ));
    }

    public function editEntry($id, $channel_id = null) {

        if ($id == 'new') {
            $channel = Channel::find($channel_id);
            $entry = Entry::create(array(
                'title'      => 'New '.\Str::singular($channel->title),
                'channel_id' => $channel_id,
                'site_id'    => $this->bones->site()->id,
                'status'     => Libraries\Bones::STATUS_DRAFT
            ));

            $entry->generateSlug();
            return Redirect::route('entry_edit', $entry->id);
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
                return Redirect::route('channel_entries', $entry->channel_id);
            }
        }

        // Show the entry form
        return $this->bones->view('admin.entries.edit', array(
            'channel' => $channel,
            'entry'   => $entry,
            'fields'  => $fields
        ));
    }

}
