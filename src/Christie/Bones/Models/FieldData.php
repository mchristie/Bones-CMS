<?php

namespace Christie\Bones\Models;

use \Eloquent;

class FieldData extends Eloquent {

    use BonesModel;

    protected $table = 'field_data';

    protected $fillable = array('entry_id', 'field_id', 'integer_data', 'string_data', 'text_data');

    private $json_fields     = array();
    private $json_attributes = array();
    private $json_defaults   = array();
    private $json_data       = array();

    /*
     *  Relationships
     */

    public function entry() {
        return $this->belongsTo('Christie\Bones\Models\Entry');
    }

    public function field() {
        return $this->belongsTo('Christie\Bones\Models\Field');
    }

    /*
     *  Work through JSON fields and decode them
     */
    public function initialize($field_type) {
        if ($field_type->json_fields)
            $this->json_fields = $field_type->json_fields;

        if ($field_type->json_attributes)
            $this->json_attributes = $field_type->json_attributes;

        if ($field_type->json_defaults)
            $this->json_defaults = $field_type->json_defaults;


        foreach ($this->json_fields as $field => $attrs) {
            try {
                $this->json_data[$field] = json_decode($this->$field);
            } catch(Exception $e) {
                $this->json_data[$field] = (object)array();
            }
        }

        return $this;
    }

    /*
     *  Check if the field should be treated as JSON, and return the attribute
     */
    public function __get($field) {
        // Is this field a JSON attribute?
        if (array_key_exists($field, $this->json_attributes)) {
            // Return the value, the default, or null
            if (
                is_object($this->json_data[ $this->json_attributes[$field] ]) &&
                property_exists($this->json_data[ $this->json_attributes[$field] ], $field)
                    ){
                return $this->json_data[ $this->json_attributes[$field] ]->$field;

            } else if (array_key_exists($field, $this->json_defaults)) {
                return $this->json_defaults[$field];

            } else {
                return null;
            }
        }

        return parent::__get($field);
    }

    /*
     *  Check if the field should be treated as JSON, and save the attribute
     */
    public function __set($field, $data) {
        if (array_key_exists($field, $this->json_attributes)) {

            if (!is_object($this->json_data[ $this->json_attributes[$field] ]))
                $this->json_data[ $this->json_attributes[$field] ] = (object)array($field => $data);

            return $this->json_data[ $this->json_attributes[$field] ]->$field = $data;
        }

        return parent::__set($field, $data);
    }

    /*
     *  Setup events, such as recoding JSON fields before save
     */
    public static function boot() {
        parent::boot();

        FieldData::saving(function($field_data) {
            $field_data->saveJsonFields();
        });
    }

    /*
     *  Loop through JSON fields and recode them
     */
    public function saveJsonFields() {
        foreach ($this->json_data as $field => $data)
            $this->$field = json_encode($data);
    }

    /*
     *  Return BOOL indicating if a field can be returned
     */
    public function hasField($field) {
        return array_key_exists($field, $this->json_attributes) || in_array($field, $this->fillable);
    }
}