<?php

namespace Christie\Bones\Models;

use \Eloquent;

class Channel extends Eloquent {

    use BonesModel;

    private $_fields = null;

    protected $fillable = array('site_id', 'title', 'slug', 'created_at', 'updated_at', 'list_view', 'entry_view', 'type', 'publish_level');

    public function site() {
        return $this->belongsTo('\Christie\Bones\Models\Site');
    }

    public function fields() {
        return $this->hasMany('\Christie\Bones\Models\Field');
    }

    public function entries() {
        return $this->hasMany('\Christie\Bones\Models\Entry');
    }

    public function widgets() {
        return $this->hasMany('\Christie\Bones\Models\Widget');
    }

    /*
     *  Return an array of all this channels fields, initialized with the provided entry
     */
    public function fieldsWithEntry(Entry $entry) {
        // Only prepare this loop once
        if ($this->_fields) return $this->_fields;

        $this->_fields = array();

        foreach ($this->fields as $field) {
            // All the model to pass field data
            $this->_fields[] = $field->initialize(null, $entry);
        }

        return $this->_fields;
    }

    /*
     *  Initialize or repair a structured data
     */
    public function structure() {
        if ($this->type != 'structured')
            throw new \Exception('Only structured channels can be rebuilt');

        /*
         *  TODO: This rebuilds every scope, even if they're not structured
         *  It should only rebuild the appropriate tree, and only if they're structured
         */

        return Entry::rebuild();
    }

    /*
     *  Return the root element of the tree
     */
    public function root() {
        return $this->entries()->orderBy('lft', 'asc')->first();
    }

    /*
     *  Return a structured array of the entries within the channel
     */
    public function entryTree() {
        return $this->root()->getDescendants()->toHierarchy();
    }
}