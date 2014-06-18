<?php

Route::any('login',     array('as' => 'login',  'uses' => 'Christie\Bones\AuthController@showLogin'));


Route::get('/images/uploads/{id}/{dims}/{file}', array('as' => 'image_sized',    'uses' => 'Christie\Bones\ImagesController@showImageResized'));

Route::group(array('prefix' => 'admin', 'before' => 'bones_auth'), function() {
    Route::get('/', 'Christie\Bones\EntriesController@showEntries');

    // Channels

    Route::get('/channels',                 array('as' => 'channels',       'uses' => 'Christie\Bones\ChannelsController@showChannels'));

    Route::any('/channel/{id}',             array('as' => 'channel_edit',   'uses' => 'Christie\Bones\ChannelsController@editChannel'));
    Route::any('/channel/{id}/fields',      array('as' => 'channel_fields', 'uses' => 'Christie\Bones\ChannelsController@editChannelFields'));
    Route::any('/channel/{ch}/field/{id}',  array('as' => 'channel_field',  'uses' => 'Christie\Bones\ChannelsController@editChannelField'));
    Route::any('/channel/{ch}/field/{id}/delete',
                                            array('as' => 'channel_field_delete', 'uses' => 'Christie\Bones\ChannelsController@deleteChannelField'));

    Route::any('/channel/{id}/entries',     array('as' => 'channel_entries','uses' => 'Christie\Bones\EntriesController@editChannelEntries'));

    // Components

    Route::get('/components',               array('as' => 'components',     'uses' => 'Christie\Bones\ComponentsController@showComponents'));
    Route::get('/component/{type}/install', array('as' => 'component_install', 'uses' => 'Christie\Bones\ComponentsController@installComponent'));
    Route::get('/component/{type}/uninstall',array('as' => 'component_uninstall', 'uses' => 'Christie\Bones\ComponentsController@uninstallComponent'));
    Route::any('/component/{type}/settings',array('as' => 'component_settings', 'uses' => 'Christie\Bones\ComponentsController@componentSettings'));

    // Entries

    Route::get('/entries/{page?}',          array('as' => 'entries',        'uses' => 'Christie\Bones\EntriesController@showEntries'));

    Route::any('/entry/{id}',               array('as' => 'entry_edit',     'uses' => 'Christie\Bones\EntriesController@editEntry'));
    Route::any('/entry/{new}/{channel_id}', array('as' => 'entry_new',      'uses' => 'Christie\Bones\EntriesController@editEntry'));

    // Images

    Route::get('/images',                   array('as' => 'images',         'uses' => 'Christie\Bones\ImagesController@showIndex'));
    Route::get('/images/album/{id}',        array('as' => 'album',          'uses' => 'Christie\Bones\ImagesController@showAlbum'));
    Route::post('/image/upload/{album_id}', array('as' => 'image_upload',   'uses' => 'Christie\Bones\ImagesController@doUpload'));
    Route::post('/images/album/{id?}',      array('as' => 'album_edit',     'uses' => 'Christie\Bones\ImagesController@editAlbum'));
    Route::post('/image/move',              array('as' => 'image_move',     'uses' => 'Christie\Bones\ImagesController@moveImage'));
    Route::post('/image/delete',            array('as' => 'image_delete',   'uses' => 'Christie\Bones\ImagesController@moveDelete'));

    // Widgets

    Route::get('/widgets',                  array('as' => 'widgets',        'uses' => 'Christie\Bones\WidgetsController@showWidgets'));
    Route::any('/widget/{id}',              array('as' => 'widget_edit',    'uses' => 'Christie\Bones\WidgetsController@editWidget'));

    // Users

    Route::get('/users',                    array('as' => 'users',          'uses' => 'Christie\Bones\UsersController@showUsers'));
    Route::any('/users/{id}',               array('as' => 'user_edit',      'uses' => 'Christie\Bones\UsersController@editUser'));

    // Snippets

    Route::get('/snippets',                 array('as' => 'snippets',       'uses' => 'Christie\Bones\SnippetsController@showSnippets'));
    Route::any('/snippet/{id}',             array('as' => 'snippet_edit',   'uses' => 'Christie\Bones\SnippetsController@editSnippet'));
});

Route::filter('bones_auth', function()
{
    if (Auth::guest()) return Redirect::guest('login');

    Bones::isAdminView(true);
});