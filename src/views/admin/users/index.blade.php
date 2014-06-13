@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Site</th>
                        <th>Level</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{$user->username}}</td>
                            <td>{{$user->site_title}}</td>
                            <td>{{$user->level_title}}</td>
                            <td>
                                <a href="{{URL::route('user_edit', $user->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>
                <a href="{{URL::route('user_edit', 'new')}}" class="btn btn-default">New user</a>
            </p>

        </div>
    </div>

@endsection