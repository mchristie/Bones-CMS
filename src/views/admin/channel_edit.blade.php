@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                <div class="form-group">
                    <label for="title">Channel title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Channel title" value="{{$channel->title}}">
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Channel slug" value="{{$channel->slug}}">
                    <p class="help-block">The channel slug is used in the URL to determine which channel to show.</p>
                </div>

                <div class="form-group">
                    <label for="slug">Type</label>
                    <select class="form-control" name="list_view">
                        <option value="structured" @if($channel->type == 'structured')selected="selected"@endif>Structured</option>
                        <option value="listing" @if($channel->type == 'listing')selected="selected"@endif>Listing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="slug">List view</label>
                    <select class="form-control" name="list_view">
                        <option value="null">(None)</option>
                        @foreach(Config::get('bones::bones.views') as $group => $views)
                            <optgroup label="{{$group}}">

                            @foreach($views as $file => $title)
                                @if($channel->list_view == $file)
                                    <option value="{{$file}}" selected="selected">{{$title}}</option>
                                @else
                                    <option value="{{$file}}">{{$title}}</option>
                                @endif
                            @endforeach

                            </optgroup>
                        @endforeach
                    </select>
                </div>

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