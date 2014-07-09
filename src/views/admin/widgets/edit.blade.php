@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-8">

            <form role="form" method="post">

                {{BonesForms::fields(array(
                    array(
                        'title'     => 'Area',
                        'name'      => 'area',
                        'options'   => 'widget_areas',
                        'type'      => 'select',
                        'value'     => $widget->area
                    ),
                    array(
                        'title'     => 'URLs',
                        'name'      => 'urls',
                        'type'      => 'text',
                        'value'     => $widget->urls
                    )
                ))}}

                @if($widget->displaysSettingsForm())
                    {{$widget->settingsForm()}}
                @endif


                <button type="submit" class="btn btn-primary" name="submit" value="save">Save changes</button>
            </form>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">Widgets</div>
                <div class="panel-body">
                    <p>Widgets are small areas of extra functionality on your website.</p>
                </div>
            </div>

        </div>
    </div>

@endsection