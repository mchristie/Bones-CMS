<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \URL;

use Christie\Bones\Models\Component;

class ComponentsController extends BonesController {

    public function showComponents() {
        return $this->bones->view('admin.components.index', array(
            'components' => $this->bones->components()
        ));
    }

    public function installComponent($type) {
        $component = $this->bones->components($type);
        $component = $component::install();

        if ($component->hasSettings()) {
            return Redirect::route('component_settings', $component->id);
        } else {
            return Redirect::route('components');
        }
    }

    public function uninstallComponent($type) {
        $component = $this->bones->components($type);
        $component = $component::uninstall();

        return Redirect::route('components');
    }

}
