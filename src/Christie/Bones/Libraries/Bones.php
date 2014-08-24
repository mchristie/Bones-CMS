<?php

namespace Christie\Bones\Libraries;

use \Config;
use \Request;
use \Route;
use \Input;
use \View;
use \App;
use \Auth;

use \Christie\Bones\Models\Site;
use \Christie\Bones\Models\Channel;
use \Christie\Bones\Models\Entry;
use \Christie\Bones\Models\Component;

class Bones {

    const LEVEL_PUBLIC      = 10;
    const LEVEL_USER        = 20;
    const LEVEL_ADMIN       = 30;
    const LEVEL_SUPER       = 40;
    const LEVEL_SYSTEM      = 50;

    const STATUS_PUBLISHED  = 10;
    const STATUS_DRAFT      = 20;
    const STATUS_DELETED    = 30;

    // Will store the current site object after construct
    public $site            = null;

    private $is_admin_view  = false;

    private $package_prefix = 'bones::';

    // Bundled fieldtypes, registered 3rd party field types will be added here
    private $field_types    = array(
        'Standard' => array(
            'checkboxes' => '\Christie\Bones\Fieldtypes\CheckboxesField',
            'email'      => '\Christie\Bones\Fieldtypes\EmailField',
            'wysiwyg'    => '\Christie\Bones\Fieldtypes\WysiwygField',
            'text'       => '\Christie\Bones\Fieldtypes\TextField',
            'textarea'   => '\Christie\Bones\Fieldtypes\TextAreaField',
            'image'      => '\Christie\Bones\Fieldtypes\ImageField'
        )
    );

    // Bundled widgets, registered 3rd party field types will be added here
    private $widgets        = array(
        'structured_menu'  => '\Christie\Bones\Widgets\StructuredMenuWidget',
        'custom_view'      => '\Christie\Bones\Widgets\CustomViewWidget'
    );

    private $components     = array();

    // Files from the config and register calls will be added here
    private $js_urls        = array();
    private $css_urls       = array();

    // Status titles
    private $status_titles  = array(
        self::STATUS_PUBLISHED  => 'Published',
        self::STATUS_DRAFT      => 'Draft',
        self::STATUS_DELETED    => 'Deleted'
    );

    /*
     *  The first time we instantiate Bones, there's a few things we need to do
     */
    public function __construct() {
        // We don't do anything in console and it breaks stuff, so abort now
        if (\App::runningInConsole()) return;

        $this->detectSite();

        return $this;
    }

    /*
     *  Detect the correct site we're on
     */
    public function detectSite() {
        // Exact domain name match
        $this->site = Site::where('url', $_SERVER['SERVER_NAME'])->first();

        // Wildcard match
        if (!$this->site)
            $this->site = Site::where('url', '*')->orWhereNull('url')->first();

        // Fail
        if (!$this->site)
            throw new \Exception('Unable to determine site');
    }

    /*
     *  Return the site object
     */
    public function site() {
        return $this->site;
    }

    /*
     *  Return a key/value array of user levels
     */
    public function levels( $id = null ) {
        $titles = array(
            self::LEVEL_PUBLIC  => 'Public',
            self::LEVEL_USER    => 'User',
            self::LEVEL_ADMIN   => 'Admin',
            self::LEVEL_SUPER   => 'Super Admin'
            // Don't include system, it should never be shown
            // self::LEVEL_SYSTEM  => 'System',
        );

        if ($id && array_key_exists($id, $titles))
            return $titles[$id];

        return $titles;
    }

    /*
     *  Pass in true/false indication if this is an admin view
     *  If NOT a admin view, filters will default to not showing unpublished content, even for admins
     */
    public function isAdminView($set = null) {
        if ($set !== null)
            $this->is_admin_view = $set;

        return $this->is_admin_view;
    }

    /*
     *  Add a field type so we can use it on demand
     *  $field_name = 'wysiwyg', $class = '\Christie\Bones\WysiwygField'
     */
    public function registerFieldType($group, $field_name, $class) {
        $this->field_types[$group][$field_name] = $class;
    }

    /*
     *  Instantiate the correct field type with the data row, field row, and entry row
     */
    public function fieldType( $field_data, $field, $entry) {
        foreach ($this->field_types as $key => $types) {
            if (array_key_exists($field->field_type, $types))
                return new $types[ $field->field_type ]( $field_data, $field, $entry );
        }
    }

    /*
     *  Return the field types array
     */
    public function fieldTypes($type = null) {
        if ($type) {
            foreach ($this->field_types as $key => $types) {
                if (array_key_exists($type, $types))
                    return $types[ $type ];

            }
        }

        return $this->field_types;
    }

    /*
     *  Return a list of components
     */
    public function components($opt = null, $only_in_menu = false) {
        // Return only installed components
        if ($opt === true) {
            $installed = array();
            $components = Component::currentSite()->get();
            foreach ($components as $component) {
                if ($only_in_menu == false || ($only_in_menu && $component->in_menu))
                    $installed[$component->type] = $this->components[$component->type];
            }
            return $installed;
        }

        if ($opt && array_key_exists($opt, $this->components))
            return $this->components[$opt];

        return $this->components;
    }

    /*
     *  Add a component so we can use it on demand
     */
    public function registerComponent($component_name, $class, $controller) {
        $this->components[$component_name] = $class;

        $component = App::make($component_name);

        Route::any($component->urlPath(), array(
            'as'        => 'component_'.$component_name,
            'before'    => 'bones_auth',
            'uses'      => $controller
        ));

        $component->configure();
    }

    /*
     *  Return an initialized instance of a component
     */
    public function component($component_name) {
        $component = Component::currentSite()->where('type', $component_name)->first();
        if (!$component) return false;

        return $component->initialize();
    }

    public function registerComponentRoutes($path, $callback) {
        Route::group(array(
            // 'prefix' => $path,
            'before' => 'bones_auth'
        ), $callback);
    }

    /*
     *  Add a widget so we can use it on demand
     */
    public function registerWidget($widget_name, $class) {
        $this->widgets[$widget_name] = $class;
    }

    /*
     *  Find a valid channel, or return null
     */
    public function channel($config = null) {
        if (is_numeric($config) || is_string($config)) {
            $channel_slug = $config;

        } else if ($config && is_array($config) && array_key_exists('channel', $config)) {
            $channel_slug = $config['channel'];

        } else if (\Request::segment(1)) {
            $channel_slug = \Request::segment(1);

        } else {
            return null;
        }

        $_channel = Channel::query();

        $channel = $_channel->currentSite()->where( is_numeric($channel_slug) ? 'id' : 'slug', $channel_slug)->first();

        return ($channel && $channel->exists()) ? $channel : null;
    }

    /*
     *  Return a list of channels
     */
    public function channels($config = null, $select = '*') {
        $channels = Channel::currentSite()->get();

        return $channels;
    }

    /*
     *  Return a list of the entries, either automatically or not
     */
    public function entries($config = null, $paging = null) {

        $_entries = Entry::query();

        if (is_array($config) && array_key_exists('with', $config))
            $_entries->with($config['with']);

        $_entries->restrict();

        // If no channel has been passed, or there is a positive value, search for a channel
        if (!$this->hasConfig('channel', $config) || $this->config('channel', $config)) {
            if ($channel = $this->channel($config))
                $_entries->where('channel_id', $channel->id);
        }

        /*
         *  If paging is true, we want to save and return the total results
         *  We must do this BEFORE we set limit / offset
         */
        if ($paging === true) {
            $this->total = $_entries->count();
            return $this->total;
        }

        // Limit the number of results
        if ($limit = $this->config('limit', $config))
            $_entries->take($limit);

        // Set which page we return
        if ($page = $this->config('page', $config))
            $_entries->forPage($page, $limit);

        // Default, calculate paging, but return the actual results
        if ($paging === null) {

            // Call this method again, but calculate paging
            $this->entries($config, true);

            // Order the results
            // TODO - this needs to be controllable!
            $_entries->orderBy('created_at', 'desc');

            // Return the results
            return $_entries->get();

        // We're not paging, so return the results
        } else if ($paging === false) {
            return $_entries->get();
        }
    }

    /*
     *  Return a single entry, protecting user level etc
     */
    public function entry($id = null, $config = null) {
        $_entry = Entry::currentSite()->visibleBy();

        if ($id) {
            return $_entry->find($id);
        } else {
            $segments = Request::segments();
            $slug = end($segments) ?: '';
            return $_entry->where('slug', $slug)->first();
        }
    }

    /*
     *  Return BOOL indicating if a config has been provided
     *  See self::config() for returning the actual value
     */
    public function hasConfig($field, $config) {
        return (is_array($config) && array_key_exists($field, $config));
    }

    /*
     *  Validate and return config values, and fall back to default if necessary
     */
    public function config($field, $config = null) {

        /*
         *  A channel config, null or * means ignore it, has no default
         */
        if ($field == 'channel') {

            if ( array_get($config, $field) && array_get($config, $field) != '*') {
                return array_get($config, $field);
            } else {
                return null;
            }

        /*
         *  Limit, returns an int, has a default
         */
        } else if ($field == 'limit') {

            if (array_get($config, $field) && is_int(array_get($config, $field))) {
                return array_get($config, $field);
            } else {
                return Config::get('bones::bones.limit');
            }

        /*
         *  Page, returns an int, default is 1
         */
        } else if ($field == 'page') {

            if (array_get($config, $field) && is_int(array_get($config, $field))) {
                return array_get($config, $field);

            } else if (Input::get('page')) {
                return Input::get('page');

            } else {
                // Check if we can find a page number in the URL
                $segments = Request::segments();
                foreach ($segments as $segment) {
                    // If a segment matches the pattern, use it
                    if (preg_match(Config::get('bones::bones.page-segment-pattern'), $segment))
                        // Return the string, with any none-numeric characters removed
                        return preg_replace('/[^0-9]/', '', $segment);
                }

                // We didn't find anything, so return 1
                return 1;
            }
        }

    }

    /*
     *  Take a view and data, merge it with global data, and return the view
     *  This will look for site specific, global, or bones default views
     */
    public function view($_view_name, $data = array(), $package = 'bones') {
        // Look for a site specific view file
        if (View::exists( $this->site()->slug .'.'. $_view_name )) {
            $view_name = $this->site()->slug .'.'. $_view_name;

        // Global view file
        } elseif (View::exists( $_view_name )) {
            $view_name = $_view_name;

        // Bones provided view file
        } elseif (View::exists( $package.'::'.$_view_name )) {
            $view_name = $package.'::'.$_view_name;

        } else {
            throw new \Exception("View file '$_view_name' not found.");
        }

        // Provide any default info we can to the view
        $global_data = array(
            'site'  => $this->site()
        );

        // Merge with any passed in data
        $view_data = array_merge($global_data, $data);

        // And use Laravel's default view generator
        return View::make($view_name, $view_data);
    }

    /*
     *  Widget functions
     */

    /*
     *  Return an instance for the widget type
     */
    public function widgetInstance($type, $widget = null) {
        if (!array_key_exists($type, $this->widgets))
            throw new \Exception("Widget type '{$type}' isn't registered.");

        return new $this->widgets[$type]($widget);
    }

    /*
     *  Return the list of widget areas
     */
    public function widgetAreas() {
        return Config::get($this->package_prefix.'bones.widget_areas');
    }

    /*
     *  Return the title for the widget area
     */
    public function widgetAreaTitle($key) {
        foreach (Config::get($this->package_prefix.'bones.widget_areas') as $area => $title) {
            if ($area == $key) return $title;
        }

        return "Widget area {$key} not found.";
    }

    /*
     *  Return the widgets for the specified area
     *  Optionally render them all and return the HTML
     */
    public function widgets($area, $render = false) {
        $_widgets = \Christie\Bones\Models\Widget::currentSite()->where('area', $area)->get();

        $widgets = array();

        foreach ($_widgets as $widget) {
            if ($widget->matchesUrl())
                $widgets[] = $widget->initialize();
        }

        if ($render) {
            $str = '';
            foreach ($widgets as $widget)
                $str .= $widget->render();

            return $str;

        } else {
            return $widgets;
        }
    }

    /*
     *  Render the JS file tags
     */
    public function jsIncludes() {
        $files = array_merge(Config::get($this->package_prefix.'bones.js_urls'), $this->js_urls);

        $html = '';
        foreach ($files as $file)
            $html .= '<script src="'.$file.'"></script>'.PHP_EOL;

        return $html;
    }

    /*
     *  Render the css file tags
     */
    public function cssIncludes() {
        $files = array_merge(Config::get($this->package_prefix.'bones.css_urls'), $this->css_urls);

        $html = '';
        foreach ($files as $file)
            $html .= '<link type="text/css" rel="stylesheet" href="'.$file.'" />'.PHP_EOL;

        return $html;
    }

    /*
     *  Add a JS url to the list of includes
     */
    public function includeJS($url) {
        if (!in_array($url, $this->js_urls))
            $this->js_urls[] = $url;
    }

    /*
     *  Add a CSS url to the list of includes
     */
    public function includeCSS($url) {
        if (!in_array($url, $this->css_urls))
            $this->css_urls[] = $url;
    }

    /*
     *  Return status titles, or a title by ID
     */
    public function statusTitles($id = null) {
        if ($id && array_key_exists($id, $this->status_titles)) {
            return $this->status_titles[$id];

        } else if ($id) {
            return null;

        } else {
            return $this->status_titles;
        }
    }

    /*
     *  Create an entry
     */
    public function createEntry($channel, $data) {
        if (is_string($channel))
            $channel = $this->channel($channel);

        if (!$channel instanceof \Christie\Bones\Models\Channel)
            throw new \Exception('Bones::createEntry - Channel not found');

        // If the auth level isn't public, or the level isn't high enough, fail
        if ($channel->publish_level > self::LEVEL_PUBLIC || !(Auth::check() && Auth::user()->level >= $channel->publish_level))
            return false;

        $entry = Entry::create(array(
            'title'      => Input::get('name'),
            'channel_id' => 7,
            'site_id'    => $this->site()->id,
            'status'     => self::STATUS_DRAFT
        ));

        if (!$entry->slug) $entry->generateSlug();

        $fields  = $channel->fieldsWithEntry($entry);

        // Populate and validate the entry first
        $entry->populate($data);
        $validates = $entry->validates();

        // Now populate and validate all the custom fields
        foreach ($fields as &$field) {
            $field->populate($data);

            if (!$field->validates()) $validates = false;
        }

        // If there were no errors, we can save everything
        if ($validates) {
            $entry->save();
            foreach ($fields as &$field) {
                $field->save();
            }
        }

        return $validates;
    }

    /*
     *  Return a snippet instance
     */
    public function snippet($key) {
        return \Christie\Bones\Models\Snippet::currentSite()->where('key', $key)->first();
    }

}