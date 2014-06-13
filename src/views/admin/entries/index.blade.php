@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Channel</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td>{{$entry->title}}</td>
                            <td>{{$entry->channel->title}}</td>
                            <td>
                                @if($entry->status == \Christie\Bones\Libraries\Bones::STATUS_DRAFT)
                                    <span class="text-warning">{{$entry->status_title}}</span>

                                @elseif($entry->status == \Christie\Bones\Libraries\Bones::STATUS_PUBLISHED)
                                    <span class="text-success">{{$entry->status_title}}</span>

                                @elseif($entry->status == \Christie\Bones\Libraries\Bones::STATUS_DELETED)
                                    <span class="text-danger">{{$entry->status_title}}</span>

                                @else
                                    {{$entry->status_title}}
                                @endif
                            </td>
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