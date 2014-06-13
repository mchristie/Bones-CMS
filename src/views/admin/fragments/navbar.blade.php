<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                @if(Auth::check())
                    <li @if(!Request::segment(2)) class="active"@endif><a href="/admin">Dashboard</a></li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Publish <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(Bones::channels() as $channel)
                                <li><a href="{{URL::route('entry_new', array('new', $channel->id))}}">{{$channel->title}}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Edit <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(Bones::channels() as $channel)
                                <li><a href="{{URL::route('channel_entries', $channel->id)}}">{{$channel->title}}</a></li>
                            @endforeach

                            <li role="presentation" class="divider"></li>
                            <li><a href="{{URL::route('entries')}}">All entries</a></li>
                        </ul>
                    </li>

                    <li @if(Request::segment(2) == 'images') class="active"@endif><a href="/admin/images">Images</a></li>

                    @if(count($components = Bones::components(true)) || count(Bones::components()))
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                Components <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">

                                @if(count($components))
                                    @foreach($components as $key => $class)
                                        <li>
                                            <a href="{{URL::route('component_'.$key)}}">
                                                {{$class::$title}}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li role="presentation" class="divider"></li>
                                @endif
                                <li><a href="{{URL::route('components')}}">Manage components</a></li>
                            </ul>
                        </li>
                    @endif

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Admin <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">

                            <li @if(Request::segment(2) == 'channels') class="active"@endif>
                                <a href="/admin/channels">Channels</a>
                            </li>

                            <li @if(Request::segment(2) == 'widgets') class="active"@endif>
                                <a href="/admin/widgets">Widgets</a>
                            </li>

                            <li @if(Request::segment(2) == 'snippets') class="active"@endif>
                                <a href="/admin/snippets">Snippets</a>
                            </li>

                            @if(Auth::user()->isLevel(\Christie\Bones\Libraries\Bones::LEVEL_SUPER))
                                <li @if(Request::segment(2) == 'users') class="active"@endif>
                                    <a href="/admin/users">Users</a>
                                </li>
                                <li @if(Request::segment(2) == 'sites') class="active"@endif>
                                    <a href="/admin/sites">Sites</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>