<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \URL;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;
use Christie\Bones\Models\Widget;

class WidgetsController extends BonesController {

    // widgets

    public function showWidgets() {
        return $this->bones->view('admin.widgets.index', array(
            'widgets' => Widget::currentSite()->get()
        ));
    }

    public function editWidget($id) {
        $widget = Widget::currentSite()->find($id);

        return $this->bones->view('admin.widgets.edit', array(
            'widget' => $widget
        ));
    }

}
