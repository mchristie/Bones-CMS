<?php

namespace Christie\Bones\Components;

use \URL;
use \Christie\Bones\Models\Component;

class BonesComponent {

    private $in_menu = true;

    // Settings JSON fields
    public $settings_json_fields     = false;
    public $settings_json_attributes = false;
    public $settings_json_defaults   = false;

    public function __construct($bones = false, $component = null) {
        if ($bones)
            $this->bones = $bones;

        if ($component)
            $this->component = $component;

    }

    public function initialize() {
        // No need to do much, override this to add functionality
    }

    public function urlPath( $extra = null, $full = false) {
        $path = '/admin/component/'.$this->name.($extra ? '/'.$extra : '');

        if ($full) {
            return URL::to($path);
        } else {
            return $path;
        }
    }

    public static function isInstalled() {
        $tmp = new static();
        return Component::currentSite()->where('type', $tmp->name)->count() > 0;
    }

    public static function install($global = false) {
        $bones = \App::make('bones');
        $tmp = new static();

        $component = Component::create(array(
            'type'    => $tmp->name,
            'in_menu' => $tmp->in_menu,
            'site_id' => $global ? null : $bones->site()->id
        ));

        return new static($bones, $component);
    }

    public static function uninstall($global = false) {
        $bones = \App::make('bones');
        $tmp = new static();

        $component = Component::currentSite()->where('type', $tmp->name)->delete();

        return new static($bones, $component);
    }

    public static function hasSettings() {
        return false;
    }

}