<?php

namespace Christie\Bones\Models;

use \Auth;

trait BonesModel {

    private $json_fields     = array();
    private $json_attributes = array();
    private $json_defaults   = array();
    private $json_data       = array();

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
        $bones = \App::make('bones');

        // If the user is logged in and a status is specific, restrict
        if (Auth::check() && $status) {
            $query->where('status', $status);

        // If not, default to only published entries
        } else if (!Auth::check() || !$bones->isAdminView()) {
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

    /*
     *  Return a title for the level field
     */
    public function getLevelTitleAttribute($value) {
        return \Bones::levels( $this->level );
    }

    /*
     *  Return a title for the site
     */
    public function getSiteTitleAttribute() {
        $site = $this->site;
        return $site ? $site->title : 'Global';
    }

    /*
     *  Return a text version of the status field
     */
    public function getStatusTitleAttribute() {
      $bones = \App::make('bones');
      return $bones->statusTitles($this->status);
    }

    /*
     *  Return the updated date formatted as the default
     */
    public function getDateAttribute() {
        return $this->date();
    }

    /*
     *  Format the specified date
     */
    public function date($format = 'jS M', $field = 'updated') {
        if (!\Str::endsWith($field, '_at')) $field .= '_at';
        return date($format, strtotime($this->$field));
    }



    /*
     *  Work through JSON fields and decode them
     */
    public function jsonFields($field_type) {

        // Save JSON fields for fields settings into the field object
        if ($this instanceof FieldData) {

            if ($field_type->data_json_fields)
                $this->json_fields = $field_type->data_json_fields;

            if ($field_type->data_json_attributes)
                $this->json_attributes = $field_type->data_json_attributes;

            if ($field_type->data_json_defaults)
                $this->json_defaults = $field_type->data_json_defaults;

        // Save JSON fields for the field data settings into the field data object
        } elseif ($this instanceof Field || $this instanceof Widget) {

            if ($field_type->settings_json_fields)
                $this->json_fields = $field_type->settings_json_fields;

            if ($field_type->settings_json_attributes)
                $this->json_attributes = $field_type->settings_json_attributes;

            if ($field_type->settings_json_defaults)
                $this->json_defaults = $field_type->settings_json_defaults;
        }

        // Decode any JSON fields which exist
        foreach ($this->json_fields as $field => $attrs) {
            try {
                $this->json_data[$field] = json_decode($this->$field);
            } catch(Exception $e) {
                $this->json_data[$field] = (object)array();
            }
        }

        return $this;
    }

    /*
     *  Check if the field should be treated as JSON, and return the attribute
     */
    public function __get($field) {
        // Is this field a JSON attribute?
        if (array_key_exists($field, $this->json_attributes)) {
            // Return the value, the default, or null
            if (
                is_object($this->json_data[ $this->json_attributes[$field] ]) &&
                property_exists($this->json_data[ $this->json_attributes[$field] ], $field)
                    ){
                return $this->json_data[ $this->json_attributes[$field] ]->$field;

            } else if (array_key_exists($field, $this->json_defaults)) {
                return $this->json_defaults[$field];

            } else {
                return null;
            }
        }

        return parent::__get($field);
    }

    /*
     *  Check if the field should be treated as JSON, and save the attribute
     */
    public function __set($field, $data) {
        // Does the field exist as a JSON pseudo field
        if (array_key_exists($field, $this->json_attributes)) {
            // Is it an object already?
            if (!is_object($this->json_data[ $this->json_attributes[$field] ]))
                // Create the object
                $this->json_data[ $this->json_attributes[$field] ] = (object)array($field => $data);

            // Set the value
            return $this->json_data[ $this->json_attributes[$field] ]->$field = $data;
        }

        return parent::__set($field, $data);
    }

    /*
     *  Pass calls over to the $this->initialized if applicable
     */
    public function __call($method, $params) {
        if ($this->initialized && method_exists($this->initialized, $method))
            return call_user_func_array(array(&$this->initialized, $method), $params);

        return parent::__call($method, $params);
    }


    /*
     *  Loop through JSON fields and recode them
     */
    public function saveJsonFields() {
        foreach ($this->json_data as $field => $data)
            $this->$field = json_encode($data);

    }

    /*
     *  Return BOOL indicating if a field can be returned
     */
    public function hasField($field) {
        return array_key_exists($field, $this->json_attributes) || in_array($field, $this->fillable) || array_key_exists($field, $this->attributes);
    }

}