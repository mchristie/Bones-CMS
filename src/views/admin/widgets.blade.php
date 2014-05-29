@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Area</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($widgets as $widget)
                        <tr>
                            <td>{{$widget->title}}</td>
                            <td>{{Bones::widgetAreaTitle($widget->area)}}</td>
                            <td>
                                <a href="{{URL::route('widget_edit', $widget->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection