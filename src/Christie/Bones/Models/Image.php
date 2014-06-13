<?php

namespace Christie\Bones\Models;

use \Eloquent;
use \File;

use Gregwar\Image\Image as Manipulate;

class Image extends Eloquent {

    use BonesModel;

    protected $fillable = array('title', 'filename', 'album_id', 'site_id', 'status');

    public function album() {
        return $this->belongsTo('\Christie\Bones\Models\Album');
    }

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

    /*
     *  This directory will contain all variations of the file
     */
    public function getBasePathAttribute() {
        return public_path('images/uploads/'.$this->id.'/');
    }

    /*
     *  Return the URL to the original image file
     */
    public function getUrlAttribute() {
        return '/images/uploads/'.$this->id.'/'.$this->filename;
    }

    /*
     *  Return a path to the image
     */
    public function getPathAttribute() {
        return $this->base_path.$this->filename;
    }

    /*
     *  Return a URL to a copy of this image at a specific size
     */
    public function url($width, $height) {
        return '/images/uploads/'.$this->id.'/'.$width.'x'.$height.'/'.$this->filename;
    }

    /*
     *  Return a path the the image at a certain size
     */
    public function path($width, $height) {
        return $this->base_path.$width.'x'.$height.'/'.$this->filename;
    }

    /*
     *  Resize the image
     */
    public function resize($width, $height) {

        Manipulate::open( $this->path )
            ->resize( $width, $height )
            ->save( $this->path($width, $height) );

        return $this->path($width, $height);
    }

    /*
     *  Remove non-URL safe characters, but preserve the last . for the extension
     */
    public static function cleanFilename($dirty_filename) {
        $split = explode('.', $dirty_filename);
        $ext = array_pop($split);
        return \Str::slug(urldecode( implode('.', $split) )).'.'.$ext;
    }

    /*
     *  Save the file to the appropriate location
     */
    public function put($data) {
        File::makeDirectory( $this->base_path );
        File::put( $this->path, $data );
    }

    /*
     *  Remove all files and delete the record
     */
    public function delete() {
        File::deleteDirectory( $this->base_path );

        return parent::delete();
    }

}