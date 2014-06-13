@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Preview</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($snippets as $snippet)
                        <tr>
                            <td>{{$snippet->key}}</td>
                            <td>{{$snippet->preview}}</td>
                            <td>
                                <a href="{{URL::route('snippet_edit', $snippet->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>
                <a href="{{URL::route('snippet_edit', 'new')}}" class="btn btn-default">New Snippet</a>
            </p>

        </div>
    </div>

@endsection