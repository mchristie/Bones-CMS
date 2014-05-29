<?php

return array(
    'view_prefix' => 'bones::',

    'views' => array(
        'Public views' => array(
            'public.channel_list'  => 'Channel list view',
            'public.channel_entry' => 'Channel single entry view',
        )
    ),

    // Default number of entries to return
    'limit' => 10,

    // This will match either blog/page-2 or blog/2
    // Any non-numeric characters will be stripped out
    'page-segment-pattern' => '/(page-)?([0-9]+)/',

    'widget_areas' => array(
        'main_menu' => 'Main menu'
    ),

    // JS files to include as standard
    'js_urls' => array(
        '/packages/christie/bones/jquery.min.js',
        '/packages/christie/bones/bootstrap/js/bootstrap.min.js'
    ),

    // JS files to include as standard
    'css_urls' => array(
        '/packages/christie/bones/bootstrap/css/bootstrap.min.css',
        '/packages/christie/bones/styles.css'
    )
);