<?php

namespace Christie\Bones;

use \Illuminate\Routing\Controllers\Controller;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;

class PublicController extends BonesController {

    public function showChannel($channel_slug, $entry_slug = null) {
        $channel = Channel::where('slug', $channel_slug)->first();

        if (!$channel) parent::auto();

        if (!$entry_slug) {
            return self::view($channel->list_view, array(
                'channel' => $channel,
                'entries' => $channel->entries()->take(10)->get()
            ));
        }

        $entry = $channel->entries()->where('slug', $entry_slug)->first();

        if (!$channel) parent::auto();

        return self::view($channel->entry_view, array(
                'channel' => $channel,
                'entry'   => $entry
            ));
    }

    public function showEntry($entry_slug = null) {
        $entry = Entry::where('slug', $entry_slug)->first();

        if (!$channel) parent::auto();

        return self::view($entry->view, array(
                'entry'   => $entry
            ));
    }
}
