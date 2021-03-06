@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Channel</th>
                        <th>Type</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($channels as $channel)
                        <tr>
                            <td>{{$channel->title}}</td>
                            <td>{{ucfirst($channel->type)}}</td>
                            <td>
                                <a href="{{URL::route('channel_entries', $channel->id)}}">Entries</a>
                                &nbsp; | &nbsp;
                                <a href="{{URL::route('entry_new', array('new', $channel->id))}}">New Entry</a>
                                &nbsp; | &nbsp;
                                <a href="{{URL::route('channel_edit', $channel->id)}}">Edit</a>
                                &nbsp; | &nbsp;
                                <a href="{{URL::route('channel_fields', $channel->id)}}">Fields</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>
                <a href="{{URL::route('channel_edit', 'new')}}" class="btn btn-default">New channel</a>
            </p>

        </div>
    </div>

@endsection