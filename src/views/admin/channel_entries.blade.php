@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <h2 class="pull-left">{{$channel->title}}</h2>

            @if($channel->type == 'structured')
                <div class="pull-right">
                    <button class="btn btn-default" data-toggle="modal" data-target="#sort-modal">Sort</button>
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td>
                                @if($channel->type == 'structured')
                                    @for($i = 1; $i < $entry->depth; $i++)
                                        - &nbsp;
                                    @endfor
                                @endif

                                {{$entry->title}}
                            </td>
                            <td>
                                <a href="{{URL::route('entry_edit', $entry->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="sort-modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Sort {{$channel->title}}</h4>
                </div>

                <div class="modal-body">

                    <ul class="sortable">
                        @foreach($structured as $entry)
                            <li id="{{$entry->id}}">
                                <div>
                                    <span class="glyphicon glyphicon-resize-vertical"></span>
                                    ({{$entry->id}}) {{$entry->title}}
                                </div>

                                @if($entry->children)
                                    <ul>
                                    @foreach($entry->children as $child)
                                        <li id="{{$child->id}}">
                                            <div>
                                                <span class="glyphicon glyphicon-resize-vertical"></span>
                                                ({{$child->id}}) {{$child->title}}
                                            </div>

                                            @if($child->children)
                                                <ul>
                                                @foreach($child->children as $granchild)
                                                    <li id="{{$granchild->id}}">
                                                        <div>
                                                            <span class="glyphicon glyphicon-resize-vertical"></span>
                                                            ({{$granchild->id}}) {{$granchild->title}}
                                                        </div>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @endif

                                        </li>
                                    @endforeach
                                    </ul>
                                @endif

                            </li>
                        @endforeach
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('additional_js')
<script>

    $('#sort-modal').on('show.bs.modal', function() {
        $('.sortable').nestedSortable({
            forcePlaceholderSize: true,
            handle: '.glyphicon',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 3,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false,
            listType: 'ul',
            protectRoot: true,
            change: function(el, x) {
                // console.dir(x);
                // console.log('Relocated item');
            }
        });
    });

    $('#sort-modal').on('hide.bs.modal', function() {
        var data = $('.sortable').nestedSortable('serialize', {startDepthCount: 0});
        console.log(data);
    });

</script>
@endsection