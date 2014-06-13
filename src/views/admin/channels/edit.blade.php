@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                {{BonesForms::fields(array(
                    array(
                        'title'     => 'Title',
                        'name'      => 'title',
                        'type'      => 'text',
                        'value'     => $channel->title
                    ),
                    array(
                        'title'     => 'Slug',
                        'name'      => 'slug',
                        'type'      => 'text',
                        'value'     => $channel->slug,
                        'help'      => 'The channel slug is used in the URL to determine which channel to show.'
                    ),
                    array(
                        'title'     => 'Site',
                        'name'      => 'site_id',
                        'type'      => 'select',
                        'options'   => 'sites',
                        'value'     => $channel->site_id,
                        // 'help'      => 'Users of this level and above will be able to add entries to this channel.'
                    ),
                    array(
                        'title'     => 'Type',
                        'name'      => 'type',
                        'type'      => 'select',
                        'options'   => 'channel_types',
                        'value'     => $channel->type,
                        // 'help'      => 'Users of this level and above will be able to add entries to this channel.'
                    ),
                    array(
                        'title'     => 'Publish level',
                        'name'      => 'publish_level',
                        'type'      => 'select',
                        'options'   => 'levels',
                        'value'     => $channel->publish_level,
                        'help'      => 'Users of this level and above will be able to add entries to this channel.'
                    ),
                    array(
                        'title'     => 'List view',
                        'name'      => 'list_view',
                        'type'      => 'select',
                        'options'   => Config::get('bones::bones.views'),
                        'value'     => $channel->list_view
                    ),
                ))}}

                <div class="form-group">
                    <label for="slug">Entry view</label>
                    <select class="form-control" name="entry_view">
                        <option value="null">(None)</option>
                        @foreach(Config::get('bones::bones.views') as $group => $views)
                            <optgroup label="{{$group}}">

                            @foreach($views as $file => $title)
                                @if($channel->entry_view == $file)
                                    <option value="{{$file}}" selected="selected">{{$title}}</option>
                                @else
                                    <option value="{{$file}}">{{$title}}</option>
                                @endif
                            @endforeach

                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
                <button type="submit" class="btn btn-default" name="submit" value="save-edit-fields">Save and edit fields</button>
            </form>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">Channels</div>
                <div class="panel-body">
                    <p>Channels contain all the content in your website.</p>
                </div>
            </div>

        </div>
    </div>

@endsection