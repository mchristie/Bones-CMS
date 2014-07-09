<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Widget extends Eloquent {

    use BonesModel;

    private $widget;
    private $bones;
    private $initialized = false;

    protected $fillable = array('site_id', 'title', 'slug', 'created_at', 'updated_at', 'list_view', 'entry_view', 'type');

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

    public function getTitleAttribute() {
        if (!$this->initialized) $this->initialize();

        return $this->initialized->title;
    }

    public function matchesUrl( $url = null ) {
        // If there's no filter, it matches
        if (!$this->urls) return true;

        if (!$url) $url = '/'.\Request::path();

dd(preg_match_all('`^'.$this->urls.'$`', $url));
        return preg_match($this->url, $url);
    }

    public function initialize() {
        // We only want to initialize this field once, so return if it exists
        if ($this->initialized) return $this->initialized;

        $bones = \App::make('bones');

        $this->initialized = $bones->widgetInstance($this->type, $this);
        $this->jsonFields($this->initialized);

        return $this;
    }

    public function populate($data) {
        $this->initialized->populate($data);

        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable) || ($this->json_attributes && array_key_exists($key, $this->json_attributes)))
                $this->$key = $value;
        }

        return parent::save();
    }

    /*
     *  Setup events, such as recoding JSON fields before save
     */
    public static function boot() {
        parent::boot();

        Widget::saving(function($field) {
            $field->saveJsonFields();
        });
    }

}