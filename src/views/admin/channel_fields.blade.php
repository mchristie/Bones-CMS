@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fields as $field)
                        <tr>
                            <td>{{$field->label}}</td>
                            <td>{{$field->name}}</td>
                            <td>{{$field->field_type}}</td>
                            <td><a href="{{URL::route('channel_field', array($channel->id, $field->id))}}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection