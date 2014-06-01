@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Channel</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td>{{$entry->title}}</td>
                            <td>{{$entry->channel->title}}</td>
                            <td>
                                <a href="{{URL::route('entry_edit', $entry->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Total results: {{$total}}</p>

        </div>
    </div>

@endsection