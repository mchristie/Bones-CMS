@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                {{BonesForms::fields(array(
                    array(
                        'title'     => 'Username',
                        'name'      => 'username',
                        'type'      => 'text',
                        'value'     => $user->username
                    ),
                    array(
                        'title'     => 'Email',
                        'name'      => 'email',
                        'type'      => 'text',
                        'value'     => $user->email
                    ),
                    array(
                        'title'     => 'Level',
                        'name'      => 'level',
                        'type'      => 'select',
                        'options'   => 'levels',
                        'value'     => $user->level
                    ),
                    array(
                        'title'     => 'Site',
                        'name'      => 'site_id',
                        'type'      => 'select',
                        'options'   => 'sites',
                        'value'     => $user->site_id
                    ),
                    array(
                        'title'     => 'Password',
                        'name'      => 'password',
                        'type'      => 'password',
                        'value'     => '',
                        'help'      => 'Leave this blank to not change the users password.'
                    )
                ))}}

                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
            </form>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">Users</div>
                <div class="panel-body">
                    <p>Users are users.</p>
                </div>
            </div>

        </div>
    </div>

@endsection