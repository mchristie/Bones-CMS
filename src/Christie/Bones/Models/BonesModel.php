<?php

namespace Christie\Bones\Models;

use \Auth;

trait BonesModel {

    /*
     *  Restrict any Bones model to specified site, or current/global by default
     */
    public function scopeCurrentSite($query, $site = false) {
        // False, default, restricts to current site or global
        if ($site === false) {
            $query->where(function($query) {
                $query->where('site_id', \Bones::site()->id)
                      ->orWhereNull('site_id');
            });
        }

        return $query;
    }

    /*
     *  Restrict any Bones model to the specified level
     */
    public function scopeVisibleBy($query, $level = false) {

        // Use the current user level, if available
        if ($level === false && Auth::check()) {
            $level = Auth::user()->level;

        // Default to public
        } else if($level === false) {
            $level = \Christie\Bones\Libraries\Bones::LEVEL_PUBLIC;

        }

        $query->where('level', '<=', $level);

        return $query;
    }

    /*
     *  Restrict any Bones model to the specified status
     */
    public function scopeStatus($query, $status = false) {
        // If the user is logged in and a status is specific, restrict
        if (Auth::check() && $status) {
            $query->where('status', $status);

        // If not, default to only published entries
        } else if (!Auth::check()) {
            $query->where('status', \Christie\Bones\Libraries\Bones::STATUS_PUBLISHED);
        }

        return $query;
    }

    /*
     *  Perform the standard filters as above
     */
    public function scopeRestrict($query) {
        return $query->currentSite()->visibleBy()->status();
    }

}