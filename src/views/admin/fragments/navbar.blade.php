<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                @if(Auth::check())
                    <li @if(!Request::segment(2)) class="active"@endif><a href="/admin">Dashboard</a></li>
                    <li @if(Request::segment(2) == 'channels') class="active"@endif>
                        <a href="/admin/channels">Channels</a>
                    </li>
                    <li @if(Request::segment(2) == 'entries') class="active"@endif>
                        <a href="/admin/entries">Entries</a>
                    </li>
                    <li @if(Request::segment(2) == 'widgets') class="active"@endif>
                        <a href="/admin/widgets">Widgets</a>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>