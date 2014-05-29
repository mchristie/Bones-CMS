<?php

namespace Christie\Bones\Widgets;

class BonesWidget {

    private $_settings = false;
    private $widget = null;

    public function __construct($widget) {
        $this->widget = $widget;
    }

    public function __toString() {
        return $this->render();
    }

    public function hasField($field) {
        return in_array($field, array('title')) ? true : false;
    }

    public function restoreSettings() {
        if ($this->widget->settings)
            $this->_settings = json_decode($this->widget->settings, true);
    }

    public function saveSettings() {
        $this->widget->settings = json_encode($this->_settings);
    }

    public function __get($field) {
        return array_get($this->_settings, $field);
    }

    public function __set($field, $val) {
        $this->_settings[$field] = $val;
    }

}