<?php

namespace Christie\Bones\Widgets;

class BonesWidget {

    private $widget = null;

    public function __construct($widget) {
        $this->widget = $widget;
    }

    public function __toString() {
        return $this->render();
    }

    public function __get($field) {
        return $this->widget->$field;
    }

    public function __set($field, $val) {
        $this->widget->$field = $val;
    }

}