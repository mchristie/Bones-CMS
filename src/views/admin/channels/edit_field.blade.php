@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                <input type="hidden" name="field_type" value="{{$field->field_type}}">

                <div class="form-group">
                    <label for="label">Field label</label>
                    <input type="text" class="form-control" id="label" name="label" placeholder="Field label" value="{{$field->label}}">
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Field name" value="{{$field->name}}">
                </div>

                @if($field->displaysSettingsForm())
                    {{$field->settingsForm()}}
                @endif

                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
            </form>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">Fields</div>
                <div class="panel-body">
                    <p>Fields contain all the content in your website.</p>
                </div>
            </div>

        </div>
    </div>

@endsection