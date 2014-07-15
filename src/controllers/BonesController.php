<?php

namespace Christie\Bones;

use \Illuminate\Routing\Controllers\Controller;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;
use Christie\Bones\Models\Widget;

use \Request;

class BonesController extends \BaseController {

    /*
     *  All bones controllers will extend this and inherit these functions
     */

    public function __construct() {
        $this->bones = \App::make('bones');
    }

    /*
     *  Do our best to find a matching channel/entry to show
     */
    public function auto() {
        // Two segments
        if (count(\Request::segments()) == 2) {
            // Find all matching entries
            $entries = Entry::restrict()->where('slug', Request::segment(2))->get();
            foreach ($entries as $entry) {
                $channel = $entry->channel;

                // If the channel slug matches, we can use the channel view if we need to
                if ($channel->slug == Request::segment(1)) {
                    return $this->bones->view($entry->view ?: $channel->entry_view, array(
                        'channel' => $channel,
                        'entry' => $entry
                    ));
                }

                // Check if we have an entry parent
                if ($parent = $entry->parent) {
                    if ($parent->slug == Request::segment(1)) {
                        $view = $entry->view ?: ($parent->view ?: $channel->entry_view);
                        return $this->bones->view($entry->view ?: $channel->entry_view, array(
                            'channel' => $channel,
                            'parent' => $parent,
                            'entry' => $entry
                        ));
                    }
                }

            }

        }

        // One segment, list a channel or find a single entry
        if (count(\Request::segments()) == 1) {
            $channel = Channel::where('slug', \Request::segment(1))->first();
            if ($channel) {
                return $this->bones->view($channel->list_view, array(
                    'channel' => $channel,
                    'entries' => $this->bones->entries(array(
                            'channel' => $channel->slug
                        ))
                ));

            }

            // Now fall back to a single entry
            $entry = Entry::where('slug', \Request::segment(1))->first();
            if ($entry) {
                $channel = $entry->channel;
                return $this->bones->view($entry->view ?: $channel->entry_view, array(
                    'channel' => $channel,
                    'entry' => $entry
                ));
            }
        }

        // If there is no slug, look for a homepage
        $entry = Entry::where(function($query) {
            $query->where('slug', '/');
            $query->orWhere('slug', '');
        })->restrict()->first();
        if ($entry) {
            $channel = $entry->channel;
            return $this->bones->view($entry->view ?: $channel->entry_view, array(
                'channel' => $channel,
                'entry' => $entry
            ));
        }

        return self::notFound();
    }

    /*
     *  Deal with 404s a bit better one day
     */
    public function notFound() {
        return 'Not found.';
    }
}
