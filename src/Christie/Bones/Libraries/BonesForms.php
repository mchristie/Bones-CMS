<?php

namespace Christie\Bones\Libraries;

class BonesForms {

    public function __construct($form = null, $bones = null) {
        $this->form = $form;
        $this->bones = $bones;
    }

    /*
     *  Wrap the laravel provided label function
     */
    public function label($name, $value = null, $options = array()) {
        return $this->form->label($name, $value, $options);
    }

    /*
     *  Add the default class if none is specified, and add standard select options
     */
    public function select($name, $list = array(), $selected = null, $options = array()) {
        // Provide a class default, if none is provided
        if (!in_array('class', $options))
            $options['class'] = 'form-control';

        // If $list is a string look for defaults
        if (is_string($list)) {

            switch($list) {
                // Provide a list of channels to select
                case 'channels':
                    $list = array();
                    foreach ($this->bones->channels() as $channel) $list[$channel->id] = $channel->title;
                    break;

                // Provide a list of widgets to select
                case 'widget_areas':
                    $list = $this->bones->widgetAreas();
                    break;
            }

        // If list isn't a string or array, we have a problem
        } else if (!is_array($list)) {
            throw new \Eception('Invalid form builder parameters');
        }

        // Pass this to the laravel provided form builder
        return $this->form->select($name, $list, $selected, $options);
    }

    /*
     *  Provide a default class if none is provided, and wrap the laravel form builder
     */
    public function input($type, $name, $value = null, $options = array()) {
        if (!in_array('class', $options))
            $options['class'] = 'form-control';

        return $this->form->input($type, $name, $value, $options );
    }

    /*
     *  Provide an entire field, including container and label
     */
    public function field( array $params ) {

        $html = $this->formGroupOpen();

        $html .= $this->label( $params['name'], $params['title'] );

        switch($params['type']) {
            case 'select':
                $html .= $this->select( $params['name'], $params['options'], $params['value'], $params);
                break;

            case 'input':
                $html .= $this->input('text', $params['name'], $params['value'], $params);
                break;
        }

        $html .= $this->formGroupClose();

        return $html;
    }

    /*
     *  Loop through and array for fields, and build them using the self::field function
     */
    public function fields( array $fields ) {
        $html = '';

        foreach($fields as $field)
            $html .= $this->field($field);

        return $html;
    }

    /*
     *  Return the default field group container
     */
    public function formGroupOpen() {
        return '<div class="form-group">';
    }

    /*
     *  Close the default field group container
     */
    public function formGroupClose() {
        return '</div>';
    }

}