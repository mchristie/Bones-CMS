<?php

namespace Christie\Bones\Components;

use \Christie\Bones\Models\Component;

class BonesComponent {

    private $in_menu = true;

    public function __construct($bones = false, $component = null) {
        if ($bones)
            $this->bones = $bones;

        if ($component)
            $this->component = $component;
    }

    public function urlPath( $extra = null) {
        return '/admin/component/'.$this->name.($extra ? '/'.$extra : '');
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