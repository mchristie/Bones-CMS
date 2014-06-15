@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Installed</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($components as $name => $class)
                        <tr>
                            <td>{{$class::$title}}</td>
                            <td>
                                @if($class::isInstalled())
                                    <span class="text-success">Yes</span>
                                @else
                                    <span class="text-danger">No</span>
                                @endif
                            </td>
                            <td>
                                @if($class::isInstalled())
                                    <a href="{{URL::route('component_uninstall', $name)}}">Uninstall</a>
                                @else
                                    <a href="{{URL::route('component_install', $name)}}">Install</a>
                                @endif

                                @if($class::hasSettings())
                                    &nbsp; | &nbsp;
                                    <a href="{{URL::route('component_settings', $name)}}">Settings</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection