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
        if (!array_key_exists('class', $options))
            $options['class'] = 'form-control';

        // If $list is a string look for defaults
        if (is_string($list)) {

            switch($list) {
                // Provide a list of channels to select
                case 'channels':
                    $list = array();
                    foreach ($this->bones->channels() as $channel)
                        $list[$channel->id] = $channel->title;
                    break;

                case 'channel_types':
                    $list = array(
                        'structured' => 'Structured',
                        'listing'    => 'Listing'
                    );
                    break;

                // Provide a list of widgets to select
                case 'widget_areas':
                    $list = $this->bones->widgetAreas();
                    break;

                // Provide a list of widgets to select
                case 'levels':
                    $list = $this->bones->levels();
                    break;

                // Provide a list of widgets to select
                case 'statuses':
                    $list = $this->bones->statusTitles();
                    break;

                // Provide a list of sites to select
                case 'sites':
                    $list = array(null => 'Global');
                    foreach( \Christie\Bones\Models\Site::get() as $site)
                        $list[$site->id] = $site->title;
                    break;
            }

        // If list isn't a string or array, we have a problem
        } else if (!is_array($list)) {
            throw new \Exception('Invalid form builder parameters');
        }

        $html_options = $this->filterParams($options, array('name', 'title', 'label', 'type', 'options', 'value'));

        // Pass this to the laravel provided form builder
        return $this->form->select($name, $list, $selected, $html_options);
    }

    /*
     *  Provide a default class if none is provided, and wrap the laravel form builder
     */
    public function input($type, $name, $value = null, $options = array()) {
        if (!array_key_exists('class', $options))
            $options['class'] = 'form-control';

        return $this->form->input($type, $name, $value, $options );
    }

    /*
     *  Create a textarea
     */
    public function textarea($name, $value = null, $options = array()) {
        if (!array_key_exists('class', $options))
            $options['class'] = 'form-control';

        if (!array_key_exists('rows', $options))
            $options['rows'] = '5';

        return $this->form->textarea($name, $value, $options);
    }

    /*
     *  Create a single checkbox
     */
    public function checkbox($name, $value, $checked, $options = array()) {
        $html = $this->formGroupOpen('checkbox');

        $html .= '<label>'.$this->form->checkbox($name, $value, $checked, $options).$name.'</label>';

        $html .= $this->formGroupClose();

        return $html;
    }

    /*
     *  Create an array of checkboxes
     */
    public function checkboxes($name, $values, $checked, $params = array()) {
        $safe_params = $this->filterParams($params, array('values', 'checked', 'title'));

        $html = '';
        foreach ($values as $value) {
            $html .= $this->checkbox($value, $value, in_array($value, $checked) ? true : null, $safe_params);
        }

        return $html;
    }

    /*
     *  Provide an entire field, including container and label
     */
    public function field( array $params ) {

        $html = $this->formGroupOpen($params['type']);

        switch($params['type']) {
            case 'select':
                $html .= $this->label( $params['name'], $params['title'] );
                $html .= $this->select( $params['name'], $params['options'], $params['value'], $params);
                break;

            case 'input':
            case 'text':
                $html .= $this->label( $params['name'], $params['title'] );
                $html .= $this->input('text', $params['name'], $params['value'], $params);
                break;

            case 'password':
                $html .= $this->label( $params['name'], $params['title'] );
                $html .= $this->input('password', $params['name'], $params['value'], $params);
                break;

            case 'textarea':
                $html .= $this->label( $params['name'], $params['title'] );
                $html .= $this->textarea( $params['name'], $params['value'], $params);
                break;

            case 'checkbox':
                $html .= $this->label( $params['name'], $params['title'] );
                $html .= $this->checkbox( $params['title'], $params['name'], $params['value'], $params['checked'], $params);
                break;

            case 'checkboxes':
                $html .= $this->checkboxes( $params['name'], $params['values'], $params['checked'], $params);
                break;
        }

        if (array_key_exists('help', $params))
            $html .= $this->fieldHelp($params['help']);

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
     *  Wrap a help string in the appropriate tags
     */
    public function fieldHelp($string) {
        return '<p class="help-block">'.$string.'</p>';
    }

    /*
     *  Return the default field group container
     */
    public function formGroupOpen( $type ) {
        if ($type == 'checkbox' || $type == 'radio') {
            $class = $type;

        } else {
            $class = 'form-group';
        }

        return '<div class="'.$class.'">';
    }

    /*
     *  Close the default field group container
     */
    public function formGroupClose() {
        return '</div>';
    }

    /*
     *  Remove unsafe options form the array
     */
    private function filterParams($params, $remove) {
        $safe_params = array();
        foreach ($params as $key => $value) {
            if (!in_array($key, $remove))
                $safe_params[$key] = $value;
        }

        return $safe_params;
    }

}