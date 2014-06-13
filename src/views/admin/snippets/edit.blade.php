@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                {{BonesForms::fields(array(
                    array(
                        'title'     => 'Key',
                        'name'      => 'key',
                        'type'      => 'text',
                        'value'     => $snippet->key
                    ),
                    array(
                        'title'     => 'Content',
                        'name'      => 'content',
                        'type'      => 'textarea',
                        'value'     => $snippet->content
                    )
                ))}}

                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
            </form>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">Snippets</div>
                <div class="panel-body">
                    <p>Snippets are small pieces of text which can be reused throughout templates.</p>
                </div>
            </div>

        </div>
    </div>

@endsection