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

                {{BonesForms::fields(array(
                    array(
                        'title'     => 'Title',
                        'name'      => 'title',
                        'type'      => 'text',
                        'value'     => $entry->title
                    ),
                    array(
                        'title'     => 'URL slug',
                        'name'      => 'slug',
                        'type'      => 'text',
                        'value'     => $entry->slug
                    ),
                    array(
                        'title'     => 'Status',
                        'name'      => 'status',
                        'type'      => 'select',
                        'options'   => 'statuses',
                        'value'     => $entry->status
                    )
                ))}}

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