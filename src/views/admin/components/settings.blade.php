@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                {{$component->settingsForm()}}

                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
            </form>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">Component settings</div>
                <div class="panel-body">
                    <p>Info...</p>
                </div>
            </div>

        </div>
    </div>

@endsection