<?php

namespace Christie\Bones\Facades;

use Illuminate\Support\Facades\Facade;

class Bones extends Facade {

    public static function getFacadeAccessor() {
        return 'bones';
    }

}