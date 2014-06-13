@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <h2>{{$channel->title}} fields</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fields as $field)
                        <tr>
                            <td>{{$field->label}}</td>
                            <td>{{$field->name}}</td>
                            <td>{{$field->field_type_title}}</td>
                            <td>
                                <a href="{{URL::route('channel_field', array($channel->id, $field->id))}}">Edit</a>
                                &nbsp; | &nbsp;
                                <a href="{{URL::route('channel_field_delete', array($channel->id, $field->id))}}" onclick="return confirm('Are you sure you want to delete this field and all of it\'s content? This cannot be undone.');">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form method="post" action="{{URL::route('channel_field', array($channel->id, 'new'))}}" class="form form-inline">

                <div class="form-group">
                    <select class="form-control" name="field_type">
                        <option>Add a new field</option>
                        @foreach($field_types as $group => $types)
                            <optgroup label="{{$group}}">

                                @foreach($types as $type => $class)
                                    <option value="{{$type}}">{{$class::$title}}</option>
                                @endforeach

                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="Add field" />
                </div>

            </form>

        </div>
    </div>

@endsection