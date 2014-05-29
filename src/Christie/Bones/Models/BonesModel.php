<?php

namespace Christie\Bones\Models;

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
        // False = detect the current user level

        // TODO: Detect current user level
        if ($level === false) $level = 10;

        $query->where('level', '<=', $level);

        return $query;
    }

    /*
     *  Restrict any Bones model to the specified status
     */
    public function scopeStatus($query, $status = false) {
        // False, detect the current user level

        // TODO: Detect appropriate level for user level
        if ($status === false) $status = \Christie\Bones\Libraries\Bones::STATUS_PUBLISHED;

        $query->where('status', $status);

        return $query;
    }

    /*
     *  Perform the standard filters as above
     */
    public function scopeRestrict($query) {
        return $query->currentSite()->visibleBy()->status();
    }

}