<?php

namespace Christie\Bones\Models;

use Baum\Node;

class Entry extends Node {

    use BonesModel;

    private $initialized = false;
    private $_fields     = array();
    private $errors      = array();

    // Restrict which rows are considered part of the 'scope' for nested sets
    protected $scoped    = array('channel_id');

    public function site() {
        return $this->belongsTo('Christie\Bones\Models\Site');
    }

    public function channel() {
        return $this->belongsTo('Christie\Bones\Models\Channel');
    }

    public function fielddata() {
        return $this->hasMany('Christie\Bones\Models\FieldData');
    }

    /*
     *  Return a link to the entry
     */
    public function getLinkAttribute() {
        // TODO: Actually return a proper link for this Entry

        return '<a href="/'.$this->slug.'">'.$this->title.'</a>';
    }

    /*
     *  This is called automatically when we access any custom field
     *  It's hopefully more efficient to query field_data all at once,
     *      instead of a distinct query for every field
     */
    public function initialize() {
        $this->initialized = true;

        foreach ($this->channel->fields as $field) {
            $field_data = null;

            // Find field data, but it'll be created if it doesn't exist anyway
            foreach ($this->fielddata as $data) {
                if ($data->field_id == $field->id) {
                    $field_data = $data;
                }
            }

            $this->_fields[$field->name] = array(
                'field'      => $field,
                'field_data' => $field_data
            );
        }
    }

    /*
     *  Fill the title and slug fields form the input array
     */
    public function populate( Array $input ) {
        if (array_key_exists('title', $input))
            $this->title = $input['title'];

        if (array_key_exists('slug', $input))
            $this->slug = $input['slug'];

        if (array_key_exists('status', $input))
            $this->status = $input['status'];
    }

    /*
     *  Check the title and slug fields are valid, and save any errors if they're not
     */
    public function validates() {
        if (strlen($this->title) < 3)
            $this->errors[] = 'The title must be at at least 3 characters long.';

        $duplicate = Entry::where('channel_id', $this->channel_id)
                          ->where('slug', $this->slug)
                          ->where('id', '!=', $this->id)
                          ->first();

        if ($duplicate)
            $this->errors[] = "The URL slug '{$this->slug}' is in use by another entry.";

        return !$this->hasErrors();
    }

    /*
     *  Return BOOL indicating if there are any validation errors to show
     */
    public function hasErrors() {
        return (count($this->errors) > 0) ? true : false;
    }

    /*
     *  Return HTML listing the errors for the entry
     */
    public function showErrors() {
        return '<ul><li>'.implode('</li><li>', $this->errors).'</li></ul>';
    }

    /*
     *  Return model attributes, or if it's the name of a custom field, initialize it and return it
     */
    public function __get($field) {

        // We need to skip this for certain fields and methods
        if ($this->__isset($field) || method_exists($this, $field))
            return parent::__get($field);

        // To return custom fields, we need to ensure this entry is initialized
        if (!$this->initialized) $this->initialize();

        // Return initialized fields if one was requested
        if (array_key_exists($field, $this->_fields))
            return $this->_fields[$field]['field']->initialize( $this->_fields[$field]['field_data'], $this );

        // Nothing else matched, pass the request up the chain, probably will never reach this
        return parent::__get($field);
    }

    public function generateSlug() {
        $this->slug = $this->channel->slug.'-'.$this->id;
        $this->save();
        return $this;
    }

    /**
     * Get a new "scoped" query builder for the Node's model.
     *
     * @param  bool  $excludeDeleted
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newNestedSetQuery($excludeDeleted = true) {
        $builder = $this->newQuery($excludeDeleted)->orderBy($this->getOrderColumnName());

        if ( $this->isScoped() ) {
            foreach($this->scoped as $scopeFld)
                $builder->where($scopeFld, '=', $this->$scopeFld);
        }

        return $builder->restrict();
    }
}