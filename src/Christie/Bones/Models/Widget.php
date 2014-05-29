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

    public function getWidgetInstance() {
        $this->initialized = true;

        $this->bones = \App::make('bones');
        $this->widget = $this->bones->widgetInstance($this->type, $this);
        $this->widget->restoreSettings();
    }

    public function __get($field) {
        if ($this->exists && !$this->initialized)
            $this->getWidgetInstance();

        if ($this->widget && $this->widget->hasField($field))
            return $this->widget->$field;

        return parent::__get($field);
    }

    public function __call($method, $params) {
        if ($this->exists && !$this->initialized)
            $this->getWidgetInstance();

        if (is_callable(array($this->widget, $method)))
            return call_user_func_array(array($this->widget, $method), $params);

        return parent::__call($method, $params);
    }

}