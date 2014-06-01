<?php

Route::any('login',     array('as' => 'login',  'uses' => 'Christie\Bones\AuthController@showLogin'));

Route::group(array('prefix' => 'admin', 'before' => 'auth'), function() {
    Route::get('/', 'Christie\Bones\AdminController@showDashboard');

    // Channels

    Route::get('/channels',                 array('as' => 'channels',       'uses' => 'Christie\Bones\ChannelsController@showChannels'));

    Route::any('/channel/{id}',             array('as' => 'channel_edit',   'uses' => 'Christie\Bones\ChannelsController@editChannel'));
    Route::any('/channel/{id}/fields',      array('as' => 'channel_fields', 'uses' => 'Christie\Bones\ChannelsController@editChannelFields'));
    Route::any('/channel/{ch}/field/{id}',  array('as' => 'channel_field',  'uses' => 'Christie\Bones\ChannelsController@editChannelField'));

    Route::any('/channel/{id}/entries',     array('as' => 'channel_entries','uses' => 'Christie\Bones\EntriesController@editChannelEntries'));

    // Entries

    Route::get('/entries/{page?}',          array('as' => 'entries',        'uses' => 'Christie\Bones\EntriesController@showEntries'));

    Route::any('/entry/{id}',               array('as' => 'entry_edit',     'uses' => 'Christie\Bones\EntriesController@editEntry'));
    Route::any('/entry/{new}/{channel_id}', array('as' => 'entry_new',      'uses' => 'Christie\Bones\EntriesController@editEntry'));

    // Widgets

    Route::get('/widgets',                  array('as' => 'widgets',        'uses' => 'Christie\Bones\WidgetsController@showWidgets'));
    Route::get('/widget/{id}',              array('as' => 'widget_edit',    'uses' => 'Christie\Bones\WidgetsController@editWidget'));
});