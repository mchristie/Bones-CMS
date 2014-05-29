<?php


Route::group(array('prefix' => 'admin'), function() {
    Route::get('/', 'Christie\Bones\AdminController@showDashboard');

    // Channels

    Route::get('/channels',                 array('as' => 'channels',       'uses' => 'Christie\Bones\AdminController@showChannels'));

    Route::any('/channel/{id}',             array('as' => 'channel_edit',   'uses' => 'Christie\Bones\AdminController@editChannel'));
    Route::any('/channel/{id}/fields',      array('as' => 'channel_fields', 'uses' => 'Christie\Bones\AdminController@editChannelFields'));
    Route::any('/channel/{id}/entries',     array('as' => 'channel_entries','uses' => 'Christie\Bones\AdminController@editChannelEntries'));
    Route::any('/channel/{ch}/field/{id}',  array('as' => 'channel_field',  'uses' => 'Christie\Bones\AdminController@editChannelField'));

    // Entries

    Route::get('/entries/{page?}',          array('as' => 'entries',        'uses' => 'Christie\Bones\AdminController@showEntries'));

    Route::any('/entry/{id}',               array('as' => 'entry_edit',     'uses' => 'Christie\Bones\AdminController@editEntry'));
    Route::any('/entry/{new}/{channel_id}', array('as' => 'entry_edit',     'uses' => 'Christie\Bones\AdminController@editEntry'));

    // Widgets

    Route::get('/widgets',                  array('as' => 'widgets',        'uses' => 'Christie\Bones\AdminController@showWidgets'));
    Route::get('/widget/{id}',              array('as' => 'widget_edit',    'uses' => 'Christie\Bones\AdminController@editWidget'));
});