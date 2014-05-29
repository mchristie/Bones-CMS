@extends('bones::layouts.public')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <h1>{{$channel->title}}</h1>

            <ul>
                @foreach($entries as $entry)
                    <li>
                        <strong>{{$entry->title}}</strong><br>
                        {{$entry->body}}
                    </li>
                @endforeach
            </ul>

        </div>
    </div>

@endsection