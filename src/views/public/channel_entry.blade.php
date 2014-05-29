@extends('bones::layouts.public')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <h1>{{$channel->title}}</h1>

            <h2>{{$entry->title}}</h2>

            <p>Field data: {{$entry->body}}</p>

            <p>Field data: {{$entry->body_two}}</p>

        </div>
    </div>

@endsection