<?php

namespace Christie\Bones;

use \Illuminate\Routing\Controllers\Controller;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;
use Christie\Bones\Models\Widget;

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
        // Two segments, assume channel / entry
        if (count(\Request::segments()) == 2) {
            $channel = Channel::where('slug', \Request::segment(1))->first();
            if ($channel) {
                $entry = $channel->entries()->where('slug', \Request::segment(2))->first();

                if ($entry) {
                    return $this->bones->view($entry->view ?: $channel->entry_view, array(
                        'channel' => $channel,
                        'entry'   => $entry
                    ));
                } else {
                    return self::notFound();
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

        return self::notFound();
    }

    /*
     *  Deal with 404s a bit better one day
     */
    public function notFound() {
        return 'Not found.';
    }
}
