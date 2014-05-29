@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                @if($entry->hasErrors())
                    <div class="alert alert-danger">
                        {{$entry->showErrors()}}
                    </div>
                @endif

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Entry title" value="{{$entry->title}}">
                </div>

                <div class="form-group">
                    <label for="title">URL slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Entry URL slug" value="{{$entry->slug}}">
                </div>

                @foreach($fields as $field)
                    @if($field->displaysEditForm())

                        <div class="form-group @if($field->hasErrors()) has-error @endif">
                            <label class="control-label" for="title">{{$field->label}}</label>
                            {{$field->editForm()}}

                            @if($field->hasErrors())
                                <span class="help-block">{{$field->showErrors()}}</span>
                            @endif
                        </div>

                    @endif
                @endforeach

                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
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