@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                <div class="form-group">
                    <label for="label">Field label</label>
                    <input type="text" class="form-control" id="label" name="label" placeholder="Field label" value="{{$field->label}}">
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Field name" value="{{$field->name}}">
                </div>

                <div class="form-group">
                    <label for="name">Field type</label>
                    <select class="form-control" name="field_type">
                        @foreach($field_types as $type => $class)

                            @if($type == $field->field_type)
                                <option value="{{$type}}" selected="selected">{{$class::$title}}</option>
                            @else
                                <option value="{{$type}}">{{$class::$title}}</option>
                            @endif

                        @endforeach
                    </select>
                </div>

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