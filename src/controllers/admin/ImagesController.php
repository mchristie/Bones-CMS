<?php

namespace Christie\Bones;

use \Christie\Bones\Models\Album;
use \Christie\Bones\Models\Image;

use \File;
use \Input;
use \URL;
use \Redirect;
use \Request;
use \Str;


class ImagesController extends BonesController {

    public function showIndex() {

        $this->bones->includeJS('/packages/christie/bones/jquery-ui-1.10.4.custom.min.js');
        $this->bones->includeCSS('/packages/christie/bones/jquery-ui-1.10.4.custom.min.css');

        $albums = Album::currentSite()->orderBy('title', 'asc')->get();

        $this->bones->includeJs('/packages/christie/bones/filedrop.min.js');

        return $this->bones->view('admin.images.index', array(
            'albums' => $albums
        ));
    }

    public function showAlbum($id) {
        $images = Image::where('album_id', $id)->get();

        return $this->bones->view('admin.images.album', array(
            'images' => $images
        ));
    }

    public function editAlbum($id = null) {
        if ($id) {

            $album = Album::currentSite()->find($id);
            $album->title = Input::get('title');
            $album->save();

        } else {
            $album = Album::create(array(
                'title'   => Input::get('title'),
                'site_id' => $this->bones->site()->id
            ));
        }

        return Redirect::route('images');
    }

    public function showImageResized($id, $dimensions, $filename) {
        list($width, $height) = explode('x', $dimensions);

        $image = Image::find($id);

        $path = $image->resize($width, $height);

        $file = new \Symfony\Component\HttpFoundation\File\File( $path );
        $mime = $file->getMimeType();

        return \Response::make( File::get( $path ), 200, array('content-type' => $mime));
    }

    public function doUpload($album_id) {
        $image = Image::create(array(
            'filename' => Image::cleanFilename( Request::header('X-File-Name') ),
            'album_id' => $album_id,
            'site_id'  => $this->bones->site()->id,
            'status'   => Libraries\Bones::STATUS_PUBLISHED
        ));
        $image->put( file_get_contents("php://input") );
    }

    public function moveImage() {
        $image = Image::currentSite()->find(Input::get('image_id'));
        $album = Album::currentSite()->find(Input::get('album_id'));

        if ($album && $image) {
            $image->album_id = $album->id;
            $image->save();

            return $album->images()->count();
        } else {
            App::abort();
        }
    }

    public function moveDelete() {
        $image = Image::currentSite()->find(Input::get('image_id'));

        if ($image) {
            $image->delete();

        } else {
            App::abort();
        }
    }

}
