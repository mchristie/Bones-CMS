<?php

namespace Christie\Bones;

use \Redirect;
use \Request;
use \Input;
use \URL;

use Christie\Bones\Models\Site;
use Christie\Bones\Models\Channel;
use Christie\Bones\Models\Entry;
use Christie\Bones\Models\Snippet;

class SnippetsController extends BonesController {

    public function showSnippets() {
        return $this->bones->view('admin.snippets.index', array(
            'snippets' => Snippet::currentSite()->get()
        ));
    }

    public function editSnippet($id) {
        if ($id == 'new') {
            $snippet = new Snippet;
        } else {
            $snippet = Snippet::currentSite()->find($id);
        }

        if (Request::getMethod() == 'POST') {
            $snippet->fill( Input::all() )->save();

            return Redirect::route('snippets');
        }

        return $this->bones->view('admin.snippets.edit', array(
            'snippet' => $snippet
        ));
    }

}
