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

    public function showWidgets() {
        return $this->bones->view('admin.widgets.index', array(
            'widgets' => Widget::currentSite()->get()
        ));
    }

    public function editWidget($id) {
        $widget = Widget::currentSite()->find($id)->initialize();

        if (Request::getMethod() == 'POST') {
            $widget->populate( Input::all() );
            $widget->save();

            return Redirect::route( 'widgets' );
        }

        return $this->bones->view('admin.widgets.edit', array(
            'widget' => $widget
        ));
    }

}
